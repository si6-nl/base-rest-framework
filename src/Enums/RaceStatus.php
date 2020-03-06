<?php

namespace Si6\Base\Enums;

/**
 * @method static static BEFORE_THE_RACE()
 * @method static static RACE_ESTABLISHED()
 * @method static static RACE_NOT_ESTABLISHED_BECAUSE_OF_ACCIDENT()
 * @method static static RACE_NOT_ESTABLISHED_BECAUSE_OF_OTHER_REASON()
 * @method static static RACE_FINISHED()
 * @method static static SOME_RACES_CANCELED()
 * @method static static STOP()
 * @method static static CHANGE_DATE()
 * @method static static CHANGE_WHEN_FOURTH_DAY_DELAY()
 * @method static static DESTROY()
 */
class RaceStatus extends Enum
{
    const BEFORE_THE_RACE                              = 0;
    const RACE_ESTABLISHED                             = 10;
    const RACE_NOT_ESTABLISHED_BECAUSE_OF_ACCIDENT     = 11;
    const RACE_NOT_ESTABLISHED_BECAUSE_OF_OTHER_REASON = 12;
    const RACE_FINISHED                                = 15;
    const SOME_RACES_CANCELED                          = 19;
    const STOP                                         = 20;
    const CHANGE_DATE                                  = 30;
    const CHANGE_WHEN_FOURTH_DAY_DELAY                 = 31;
    const DESTROY                                      = 40;
}