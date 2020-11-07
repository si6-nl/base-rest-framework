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
                $select    = $value === null ? 'null' : '?';
                $newField  = "new_" . $field;
                $selects[] = !empty($joins) ? "$select" : "$select AS $newField";
                if ($value !== null) {
                    $bindings[] = $value;
                }
            }
            $joins[] = 'SELECT ' . implode(', ', $selects);

            if (!$sets) {
                foreach ($attribute as $field => $value) {
                    $newField  = "new_" . $field;
                    $sets[] = "t1.$field = t2.$newField";
                }
            }
        }

        $mappingKeys = [];
        foreach ($indexKeys as $key) {
            $newKey = "new_" . $key;
            $mappingKeys[] = "(t1.$key = t2.$newKey OR (t1.$key IS NULL AND t2.$newKey IS NULL))";
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
            "UPDATE `{$table}` AS t1, ({$joins}) AS t2 SET {$sets} WHERE {$mappingKeys}";

        DB::update($query, $bindings);
    }
}
