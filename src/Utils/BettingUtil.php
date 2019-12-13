<?php

namespace Si6\Base\Utils;

use Si6\Base\Enums\VoteType;

trait BettingUtil
{
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

        return $positionsWins;
    }

    protected function keyByPosition($item)
    {
        return $item->vote_type . ($item->position_1 ?: '') . ($item->position_2 ?: '') . ($item->position_3 ?: '');
    }
}
