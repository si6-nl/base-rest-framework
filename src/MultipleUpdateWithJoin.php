<?php

namespace Si6\Base;

use Illuminate\Support\Facades\DB;

trait MultipleUpdateWithJoin
{
    protected function multipleUpdateWithJoin($attributes, $indexKeys)
    {
        $table = DB::getTablePrefix() . $this->getTable();

        $joins    = [];
        $bindings = [];
        $sets     = [];

        foreach ($attributes as $attribute) {
            $selects = [];
            foreach ($attribute as $field => $value) {
                $select     = $value === null ? 'null' : '?';
                $selects[]  = !empty($joins) ? "$select" : "$select AS `$field`";
                if ($value !== null) {
                    $bindings[] = $value;
                }
            }
            $joins[] = 'SELECT ' . implode(', ', $selects);

            if (!$sets) {
                foreach ($attribute as $field => $value) {
                    $sets[] = "t1.`$field` = t2.`$field`";
                }
            }
        }

        $mappingKeys = [];
        foreach ($indexKeys as $key) {
            $mappingKeys[] = "((t1.`$key` = t2.`$key`) OR (t1.`$key` IS NULL AND t2.`$key` IS NULL))";
        }

        if (!$joins || !$mappingKeys || !$sets) {
            return;
        }

        $sets[]     = "t1.`updated_at` = ?";
        $bindings[] = now();

        $joins       = implode(' UNION ALL ', $joins);
        $mappingKeys = implode(' AND ', $mappingKeys);
        $sets        = implode(', ', $sets);

        $query = /** @lang text */
            "UPDATE `{$table}` t1 JOIN ({$joins}) t2 ON {$mappingKeys} SET {$sets}";

        DB::update($query, $bindings);
    }
}
