<?php

namespace Si6\Base;

use Illuminate\Support\Facades\DB;

trait MultipleUpdateWithJoin
{
    protected function multipleUpdateWithJoin($data, $indexKeys)
    {
        $table = DB::getTablePrefix() . $this->getTable();

        $joins    = [];
        $bindings = [];
        $sets     = [];

        foreach ($data as $attributes) {
            $selects = [];
            foreach ($attributes as $field => $attribute) {
                $select     = $attribute === null ? 'null' : '?';
                $selects[]  = !empty($joins) ? $select : "$select AS $field";
                $bindings[] = $attribute;
            }
            $joins[] = 'SELECT ' . implode(', ', $selects);

            if (!$sets) {
                foreach ($attributes as $field => $attribute) {
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
