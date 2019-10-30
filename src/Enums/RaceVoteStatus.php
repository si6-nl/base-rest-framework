<?php

namespace Si6\Base\Enums;

/**
 * @method static static BEFORE()
 * @method static static PROCESSING()
 * @method static static FINISHED()
 * @method static static STOPPED()
 */
class RaceVoteStatus extends Enum
{
    const BEFORE     = 0;
    const PROCESSING = 1;
    const FINISHED   = 2;
    const STOPPED    = 3;
}
