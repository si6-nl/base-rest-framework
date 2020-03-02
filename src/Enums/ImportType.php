<?php

namespace Si6\Base\Enums;

/**
 * @method static static ORGANIZATION_CALENDAR()
 * @method static static ORGANIZATION_RACES()
 * @method static static ORGANIZATION_DETAIL_RACE()
 * @method static static ORGANIZATION_RACE_RESULT()
 * @method static static ORGANIZATION_PLAYERS()
 * @method static static ORGANIZATION_TIME_TRIAL_RESULT()
 * @method static static VOTE_INFORM_VOTE()
 * @method static static VOTE_INFORM_ODDS()
 * @method static static VOTE_INFORM_REFUND()
 * @method static static VOTE_GET_VOTE_TYPES()
 */
class ImportType extends Enum
{
    const ORGANIZATION_CALENDAR          = 1;
    const ORGANIZATION_RACES             = 2;
    const ORGANIZATION_DETAIL_RACE       = 3;
    const ORGANIZATION_RACE_RESULT       = 4;
    const ORGANIZATION_PLAYERS           = 5;
    const ORGANIZATION_TIME_TRIAL_RESULT = 6;
    const VOTE_INFORM_VOTE               = 7;
    const VOTE_INFORM_ODDS               = 8;
    const VOTE_INFORM_REFUND             = 9;
    const VOTE_GET_VOTE_TYPES            = 10;

    /**
     * Types sync data in the race service
     *
     * @return array
     */
    public static function typesInRaceService()
    {
        return [
            self::ORGANIZATION_CALENDAR,
            self::ORGANIZATION_RACES,
            self::ORGANIZATION_DETAIL_RACE,
            self::ORGANIZATION_RACE_RESULT,
            self::ORGANIZATION_PLAYERS,
            self::ORGANIZATION_TIME_TRIAL_RESULT
        ];
    }

    /**
     * Types sync data in the betting service
     *
     * @return array
     */
    public static function typesInBettingService()
    {
        return [
            self::VOTE_INFORM_VOTE,
            self::VOTE_INFORM_ODDS,
            self::VOTE_INFORM_REFUND,
            self::VOTE_GET_VOTE_TYPES
        ];
    }
}
