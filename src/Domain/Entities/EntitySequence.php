<?php

namespace Si6\Base\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="entity_sequences")
 */
class EntitySequence
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $entity;
    /**
     * @var int
     * @ORM\Column(type="bigint", options="{unsigned=true}")
     */
    private $nextValue;

    /**
     * EntitySequence constructor.
     *
     * @param string $entity
     * @param int $nextValue
     */
    public function __construct(string $entity, int $nextValue)
    {
        $this->entity = $entity;
        $this->nextValue = $nextValue;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return int
     */
    public function getNextValue(): int
    {
        return $this->nextValue;
    }

    /**
     * @param int $nextValue
     */
    public function updateNextValue(int $nextValue): void
    {
        $this->nextValue += $nextValue;
    }
}
