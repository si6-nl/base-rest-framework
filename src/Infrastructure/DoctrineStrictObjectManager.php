<?php

namespace Si6\Base\Infrastructure;

use DB;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Si6\Base\Domain\Entities\EntitySequence;
use Si6\Base\Exceptions\ServiceException;
use Si6\Base\Utils\UniqueIdentity;

final class DoctrineStrictObjectManager extends EntityManagerDecorator implements StrictObjectManager
{
    public function __construct(EntityManagerInterface $wrapped)
    {
        parent::__construct($wrapped);
        $tablePrefix = new TablePrefix(config('app.name') . '_');
        $evm         = $this->getEventManager();
        $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);
    }

    /**
     * @param string $entityName
     * @param $id
     * @return object
     */
    public function findOrFail(string $entityName, $id)
    {
        $entity = $this->wrapped->find($entityName, $id);

        if ($entity === null) {
            throw new ServiceException(basename(str_replace('\\', '/', $entityName)) . ' not found');
        }

        return $entity;
    }

    /**
     * @param array $entities
     */
    public function multiPersist(array $entities): void
    {
        foreach ($entities as $entity) {
            if ($entity !== null) {
                $this->persist($entity);
            }
        }
    }

    /**
     * @param string $entityName
     * @param int $count
     * @return int[]
     */
    public function generateIds(string $entityName, int $count): array
    {
        $nextSequence = $this->increaseEntitySequence($entityName, $count);

        return array_map(
            function ($id) {
                return UniqueIdentity::id($id);
            },
            range($nextSequence - $count, $nextSequence - 1)
        );
    }

    /**
     * @param string $entityName
     * @param int $count
     * @return int
     */
    private function increaseEntitySequence(string $entityName, int $count): int
    {
        $entitySequence = new EntitySequence($entityName, $count);

        return $this->transactional(
            function () use ($entitySequence, $count) {
                $sequence = $this->find(
                    EntitySequence::class,
                    $entitySequence->getEntity(),
                    LockMode::PESSIMISTIC_WRITE
                );

                if (!$sequence) {
                    $entitySequence->updateNextValue(1);
                    $this->persist($entitySequence);
                    $nextValue = $entitySequence->getNextValue();
                } else {
                    $sequence->updateNextValue($count);
                    $this->persist($sequence);
                    $nextValue = $sequence->getNextValue();
                }

                $this->flush();

                return $nextValue;
            }
        );
    }

    /**
     * @param string $entityName
     * @return int
     */
    public function generateId(string $entityName): int
    {
        $ids = $this->generateIds($entityName, 1);

        return $ids[0];
    }
}