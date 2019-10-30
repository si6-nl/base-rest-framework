<?php

namespace Si6\Base\Enums;

/**
 * @method static static START()
 * @method static static FINISH()
 * @method static static STOP()
 * @method static static CHANGE_EXPIRE()
 */
class VoteChangeStatus extends Enum
{
    const START         = 100;
    const FINISH        = 200;
    const STOP          = 300;
    const CHANGE_EXPIRE = 400;
}
