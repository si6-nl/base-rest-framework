<?php

namespace Si6\Base\Utils;

class OddsUtil
{
    public function permutations(array $set, $subset_size = null)
    {
        $combinations = $this->combinations($set, $subset_size);
        $permutations = array();
        foreach ($combinations as $combination) {
            $permutations = array_merge($permutations, $this->findPermutations($combination));
        }
        return $permutations;
    }

    private function findPermutations($set)
    {
        if (count($set) <= 1) {
            return array($set);
        }
        $permutations = array();
        list($key, $val) = $this->arrayShiftAssoc($set);
        $sub_permutations = $this->findPermutations($set);
        foreach ($sub_permutations as $permutation) {
            $permutations[] = array_merge(array($key => $val), $permutation);
        }
        $set[$key] = $val;
        $start_key = $key;
        $key = $this->firstKey($set);
        while ($key != $start_key) {
            list($key, $val) = $this->arrayShiftAssoc($set);
            $sub_permutations = $this->findPermutations($set);
            foreach ($sub_permutations as $permutation) {
                $permutations[] = array_merge(array($key => $val), $permutation);
            }
            $set[$key] = $val;
            $key = $this->firstKey($set);
        }
        return $permutations;
    }

    private function arrayShiftAssoc(array &$array)
    {
        foreach ($array as $key => $val) {
            unset($array[$key]);
            break;
        }
        return array($key, $val);
    }

    private function firstKey($array)
    {
        foreach ($array as $key => $val) {
            break;
        }
        return $key;
    }

    public function combinations(array $set, $subset_size = null)
    {
        $set_size = count($set);
        if (is_null($subset_size)) {
            $subset_size = $set_size;
        }
        if ($subset_size >= $set_size) {
            return array($set);
        } else if ($subset_size == 1) {
            return array_chunk($set, 1);
        } else if ($subset_size == 0) {
            return array();
        }
        $combinations = array();
        $set_keys = array_keys($set);
        $this->_pointers = array_slice(array_keys($set_keys), 0, $subset_size);
        $combinations[] = $this->getCombination($set);
        while ($this->advancePointers($subset_size - 1, $set_size - 1)) {
            $combinations[] = $this->getCombination($set);
        }
        return $combinations;
    }

    private function getCombination($set)
    {
        $set_keys = array_keys($set);
        $combination = array();
        foreach ($this->_pointers as $pointer) {
            $combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
        }
        return $combination;
    }

    private function advancePointers($pointer_number, $limit)
    {
        if ($pointer_number < 0) {
            return false;
        }
        if ($this->_pointers[$pointer_number] < $limit) {
            $this->_pointers[$pointer_number]++;
            return true;
        } else {
            if ($this->advancePointers($pointer_number - 1, $limit - 1)) {
                $this->_pointers[$pointer_number] =
                    $this->_pointers[$pointer_number - 1] + 1;
                return true;
            } else {
                return false;
            }
        }
    }
}