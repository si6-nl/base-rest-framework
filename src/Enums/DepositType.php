<?php

namespace Si6\Base\Enums;

/**
 * @method static static CREDIT_CARD()
 * @method static static CONVENIENCE_STORE()
 * @method static static NET_BANK()
 * @method static static ATM()
 * @method static static REFUND()
 * @method static static REFUND_EXPIRATION()
 * @method static static EXPIRATION()
 * @method static static REFUND_RETURN()
 */
class DepositType extends Enum
{
    const CREDIT_CARD       = 0;
    const CONVENIENCE_STORE = 1;
    const NET_BANK          = 2;
    const ATM               = 3;
    const REFUND            = 4;
    const REFUND_EXPIRATION = 5;
    const EXPIRATION        = 6;
    const REFUND_RETURN     = 7;
    const TICKET            = 8;
    const GRANTED_BY_ADMIN  = 9;
    const EXPIRED_BY_ADMIN  = 10;

    public static function typeForUserHistory()
    {
        return [
            'credit_card',
            'convenience_store',
            'net_bank',
            'atm',
            'refund',
            'refund_expiration',
            'expiration',
            'refund_return',
            'ticket',
            'granted_by_admin',
            'expired_by_admin',
        ];
    }
}
