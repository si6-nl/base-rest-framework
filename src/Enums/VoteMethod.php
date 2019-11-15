<?php

namespace Si6\Base\Enums;

/**
 * @method static static IMAGE()
 * @method static static POPULAR()
 * @method static static ODDS()
 * @method static static PLAYER()
 */
class VoteMethod extends Enum
{
    const IMAGE   = 0;
    const POPULAR = 1;
    const ODDS    = 2;
    const PLAYER  = 3;
}
