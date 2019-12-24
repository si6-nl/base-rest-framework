<?php

namespace Si6\Base\Enums;

/**
 * @method static static VOTE()
 * @method static static EXPIRATION()
 * @method static static TRANSFER_TO_BANK()
 * @method static static TRANSFER_TO_DEPOSIT_IN_REFUND_SCREEN()
 * @method static static TRANSFER_TO_DEPOSIT_IN_DEPOSIT_SCREEN()
 */
class UserRefundType extends Enum
{
    const VOTE                                  = 0;
    const EXPIRATION                            = 1;
    const TRANSFER_TO_BANK                      = 2;
    const TRANSFER_TO_DEPOSIT_IN_REFUND_SCREEN  = 3;
    const TRANSFER_TO_DEPOSIT_IN_DEPOSIT_SCREEN = 4;
}
