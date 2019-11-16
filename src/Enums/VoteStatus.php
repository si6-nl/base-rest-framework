<?php

namespace Si6\Base\Enums;

/**
 * @method static static WAITING()
 * @method static static SUCCESS()
 * @method static static FAILURE()
 * @method static static FAILURE_AND_RETURN()
 */
class VoteStatus extends Enum
{
    const WAITING            = 0;
    const SUCCESS            = 1;
    const FAILURE            = 2;
    const FAILURE_AND_RETURN = 3;
}
