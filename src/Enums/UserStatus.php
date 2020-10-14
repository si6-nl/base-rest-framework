<?php

namespace Si6\Base\Enums;

/**
 * @method static static NEWBIE()
 * @method static static OFFICIAL()
 * @method static static IDENTITY_VERIFIED()
 * @method static static LIMITED()
 * @method static static SUSPENDED()
 * @method static static EXPELLED()
 * @method static static LEFT()
 * @method static static DELETED()
 * @method static static NEWBIE_MUST_CHANGE_PASSWORD()
 */
class UserStatus extends Enum
{
    const NEWBIE                      = 0;
    const OFFICIAL                    = 1;
    const IDENTITY_VERIFIED           = 2;
    const LIMITED                     = 3;
    const SUSPENDED                   = 4;
    const EXPELLED                    = 5;
    const LEFT                        = 6;
    const DELETED                     = 7;
    const NEWBIE_MUST_CHANGE_PASSWORD = 8;

    public static function cannotLogin()
    {
        return [
            self::SUSPENDED,
            self::EXPELLED,
            self::LEFT,
            self::DELETED,
        ];
    }
}
