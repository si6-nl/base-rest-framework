<?php

namespace Si6\Base\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;

interface StrictObjectManager extends EntityManagerInterface
{
    /**
     * @param string $entityName
     * @param $id
     * @return object
     */
    public function findOrFail(string $entityName, $id);

    /**
     * @param string $entityName
     * @return int
     */
    public function generateId(string $entityName);

    /**
     * @param string $entityName
     * @param int $count
     * @return int[]
     */
    public function generateIds(string $entityName, int $count);
}