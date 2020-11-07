<?php

namespace Si6\Base;

use Illuminate\Support\Facades\DB;

trait MultipleUpdateWithSubQuery
{
    protected function multipleUpdateWithSubQuery($attributes, $indexKeys)
    {
        $table = DB::getTablePrefix() . $this->getTable();

        $updates  = [];
        $bindings = [];
        $sets     = [];

        foreach ($attributes as $attribute) {
            $selects = [];
            foreach ($attribute as $field => $value) {
                $select    = $value === null ? 'null' : '?';
                $selects[] = !empty($updates) ? "$select" : "$select AS $field";
                if ($value !== null) {
                    $bindings[] = $value;
                }
            }
            $updates[] = 'SELECT ' . implode(', ', $selects);

            if (!$sets) {
                foreach ($attribute as $field => $value) {
                    $sets[] = "t.$field = u.$field";
                }
            }
        }

        $mappingKeys = [];
        foreach ($indexKeys as $key) {
            $mappingKeys[] = "(t.$key = u.$key OR (t.$key IS NULL AND u.$key IS NULL))";
        }

        if (!$updates || !$mappingKeys || !$sets) {
            return;
        }

        if (count($updates) == 1) {
            // This bug happen when $updates don't have UNION ALL, so weird.
            $updates[] = $updates[0];
            $bindings  = array_merge($bindings, $bindings);
        }

        $sets[]     = "t1.`updated_at` = ?";
        $bindings[] = now();

        $updates     = implode(' UNION ALL ', $updates);
        $mappingKeys = implode(' AND ', $mappingKeys);
        $sets        = implode(', ', $sets);

        $query = /** @lang text */
            "UPDATE `{$table}` t, ({$updates}) u SET {$sets} WHERE {$mappingKeys}";

        DB::update($query, $bindings);
    }
}
