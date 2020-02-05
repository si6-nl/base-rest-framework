<?php

namespace Si6\Base\Enums;

/**
 * @method static static WAITING()
 * @method static static SUCCESS()
 * @method static static OVERDUE_PAYMENT_DEADLINE()
 * @method static static EXPIRED()
 * @method static static FAILURE()
 */
class DepositStatus extends Enum
{
    const WAITING                  = 0;
    const SUCCESS                  = 1;
    const OVERDUE_PAYMENT_DEADLINE = 2;
    const EXPIRED                  = 3;
    const FAILURE                  = 4;
}
