<?php

namespace Si6\Base\Enums;

class AdminChangeUserStatus extends Enum
{
    const SUSPENDED        = 0;
    const REMOVE_SUSPENDED = 1;
    const EXPELLED         = 2;

    public static function mappingChangeUserStatus($changingStatus)
    {
        $mapping = [
            self::SUSPENDED => UserStatus::SUSPENDED,
            self::EXPELLED  => UserStatus::EXPELLED,
        ];

        return $mapping[$changingStatus] ?? UserStatus::SUSPENDED;
    }
}
