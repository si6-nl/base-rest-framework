<?php

namespace Si6\Base\Utils;

use Si6\Base\Enums\VoteType;

trait BettingUtil
{
    protected $_pointers;

    protected function getPositionsWins(array $ranks, array $players)
    {
        if (!$ranks || !$players) {
            return [];
        }

        $players = collect($players);

        $brackets = $players->mapWithKeys(function ($player) {
            return [$player->arrangement->bicycle_number ?? 0 => $player->arrangement->bracket_number ?? 0];
        });

        $positions = collect($ranks)->map(function ($rank) {
            return explode(',', $rank);
        });

        $positionsWins = [];

        $numberOfPlayers = $players->count();

        foreach ($positions[0] as $first) {
            $positionsWins[VoteType::WIN][]        = [$first];
            $positionsWins[VoteType::PLACE_SHOW][] = [$first];
        }

        foreach ($positions[1] as $second) {
            $positionsWins[VoteType::PLACE_SHOW][] = [$second];
        }

        if ($numberOfPlayers >= 8) {
            foreach ($positions[2] as $third) {
                $positionsWins[VoteType::PLACE_SHOW][] = [$third];
            }
        }

        $sort = function ($array) {
            sort($array);

            return $array;
        };

        foreach ($positions[0] as $first) {
            foreach ($positions[1] as $second) {
                $positionsWins[VoteType::EXACTA][]           = [$first, $second];
                $positionsWins[VoteType::QUINELLA][]         = $sort([$first, $second]);
                $positionsWins[VoteType::BRACKET_EXACTA][]   = [$brackets[$first] ?? 0, $brackets[$second] ?? 0];
                $positionsWins[VoteType::BRACKET_QUINELLA][] = $sort([$brackets[$first] ?? 0, $brackets[$second] ?? 0]);

                foreach ($positions[2] as $third) {
                    $positionsWins[VoteType::TRIFECTA][] = [$first, $second, $third];
                    $positionsWins[VoteType::TRIO][]     = $sort([$first, $second, $third]);
                    $positionsWins[VoteType::WIDE][]     = $sort([$first, $second]);
                    $positionsWins[VoteType::WIDE][]     = $sort([$second, $third]);
                    $positionsWins[VoteType::WIDE][]     = $sort([$third, $first]);
                }
            }
        }

        $combinations = $this->combinations(array_merge(
            array_values($positions[0]),
            array_values($positions[1]),
            array_values($positions[2])
        ), 2);

        $positionsWins[VoteType::WIDE] = collect($combinations)->map(function ($combination) use ($sort) {
            return $sort(array_values($combination));
        })->toArray();

        return $positionsWins;
    }

    protected function keyByPosition($item)
    {
        return $item->vote_type . ($item->position_1 ?: '') . ($item->position_2 ?: '') . ($item->position_3 ?: '');
    }

    public function permutations(array $set, $subset_size = null)
    {
        $combinations = $this->combinations($set, $subset_size);
        $permutations = [];
        foreach ($combinations as $combination) {
            $permutations = array_merge($permutations, $this->findPermutations($combination));
        }

        return $permutations;
    }

    private function findPermutations($set)
    {
        if (count($set) <= 1) {
            return [$set];
        }
        $permutations = [];
        [$key, $val] = $this->arrayShiftAssoc($set);
        $sub_permutations = $this->findPermutations($set);
        foreach ($sub_permutations as $permutation) {
            $permutations[] = array_merge([$key => $val], $permutation);
        }
        $set[$key] = $val;
        $start_key = $key;
        $key       = $this->firstKey($set);
        while ($key != $start_key) {
            [$key, $val] = $this->arrayShiftAssoc($set);
            $sub_permutations = $this->findPermutations($set);
            foreach ($sub_permutations as $permutation) {
                $permutations[] = array_merge([$key => $val], $permutation);
            }
            $set[$key] = $val;
            $key       = $this->firstKey($set);
        }

        return $permutations;
    }

    private function arrayShiftAssoc(array &$array)
    {
        foreach ($array as $key => $val) {
            unset($array[$key]);
            break;
        }

        return [$key, $val];
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
            return [$set];
        } else {
            if ($subset_size == 1) {
                return array_chunk($set, 1);
            } else {
                if ($subset_size == 0) {
                    return [];
                }
            }
        }
        $combinations    = [];
        $set_keys        = array_keys($set);
        $this->_pointers = array_slice(array_keys($set_keys), 0, $subset_size);
        $combinations[]  = $this->getCombination($set);
        while ($this->advancePointers($subset_size - 1, $set_size - 1)) {
            $combinations[] = $this->getCombination($set);
        }

        return $combinations;
    }

    private function getCombination($set)
    {
        $set_keys    = array_keys($set);
        $combination = [];
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
