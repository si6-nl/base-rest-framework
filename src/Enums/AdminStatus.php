<?php

namespace Si6\Base\Enums;

/**
 * @method static static ACTIVE()
 * @method static static SUSPENDED()
 * @method static static DELETED()
 */
class AdminStatus extends Enum
{
    const ACTIVE    = 0;
    const SUSPENDED = 1;
    const DELETED   = 2;
}
