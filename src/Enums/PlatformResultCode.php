<?php

namespace Si6\Base\Enums;

class PlatformResultCode extends Enum
{
    const HTTP_OK                                = 100;
    const RACE_DOES_NOT_EXIST                    = 600;
    const RACE_HAS_BEEN_FINISHED                 = 601;
    const RACE_HAS_BEEN_SUSPENDED_OR_RESCHEDULED = 602;
    const VOTE_ID_DOES_NOT_EXIST                 = 700;
    const VOTE_SITE_ID_DOES_NOT_EXIST            = 701;
    const VOTE_SITE_ID_HAS_ALREADY_BEEN_TAKEN    = 702;
    const VOTE_HAS_NOT_STARTED_YET               = 710;
    const VOTE_HAS_BEEN_EXPIRED                  = 711;
    const VOTE_HAS_BEEN_SUSPENDED                = 712;
    const VOTE_HAS_BEEN_CANCELED                 = 713;
    const BETTING_DID_NOT_SOLD                   = 720;
    const BETTING_HAS_BEEN_CANCELED              = 721;
    const BRACKET_NUMBER_DOES_NOT_EXIST          = 730;
    const BRACKET_NUMBER_HAS_BEEN_MISSED         = 731;
    const ODDS_DOES_NOT_EXIST                    = 740;
    const BICYCLE_NUMBER_IS_INVALID              = 741;
    const VOTE_NUMBER_IS_INVALID                 = 750;
    const BAD_REQUEST                            = 800;
    const UNPROCESSABLE_ENTITY                   = 801;
    const SOME_FIELD_ARE_REQUIRED                = 802;
    const OUT_OF_RANGE_OF_POSSIBLE_VALUE         = 803;
    const OTHER_VALIDATION_ERROR                 = 804;
    const DATA_DOES_NOT_REGISTERED               = 805;
    const DATA_HAS_ALREADY_BEEN_TAKEN            = 806;
    const INTERNAL_SERVER_ERROR                  = 900;
}
