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
                $selects[]  = !empty($joins) ? "$select" : "$select AS $field";
                if ($value !== null) {
                    $bindings[] = $value;
                }
            }
            $joins[] = 'SELECT ' . implode(', ', $selects);

            if (!$sets) {
                foreach ($attribute as $field => $value) {
                    $sets[] = "t.$field = j.$field";
                }
            }
        }

        $mappingKeys = [];
        foreach ($indexKeys as $key) {
            $mappingKeys[] = "((t.$key = j.$key) OR (t.$key IS NULL AND j.$key IS NULL))";
        }

        if (!$joins || !$mappingKeys || !$sets) {
            return;
        }

        $sets[]     = "t.updated_at = ?";
        $bindings[] = now();

        $joins       = implode(' UNION ALL ', $joins);
        $mappingKeys = implode(' AND ', $mappingKeys);
        $sets        = implode(', ', $sets);

        $query = /** @lang text */
            "UPDATE `{$table}` t JOIN ({$joins}) j ON {$mappingKeys} SET {$sets}";

        DB::update($query, $bindings);
    }
}
