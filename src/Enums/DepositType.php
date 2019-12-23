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
}
