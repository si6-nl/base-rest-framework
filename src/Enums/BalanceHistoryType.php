<?php

namespace Si6\Base\Enums;

/**
 * @method static static DEPOSIT()
 * @method static static VOTE()
 * @method static static RETURN_VOTE_FAILURE()
 */
class BalanceHistoryType extends Enum
{
    const DEPOSIT             = 0;
    const VOTE                = 1;
    const RETURN_VOTE_FAILURE = 2;

    /**
     * @param $type
     * @return bool
     */
    public static function isSpentType($type)
    {
        return in_array($type, [
            BalanceHistoryType::VOTE,
        ]);
    }
}
