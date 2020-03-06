<?php

namespace Si6\Base\Enums;

/**
 * @method static static NOT_REFUND_RETURN()
 * @method static static REFUND_FOR_MISSING_BICYCLE()
 * @method static static REFUND_FOR_CANCELLED_RACE()
 */
class RefundReturnType extends Enum
{
    const NOT_REFUND_RETURN          = 0;
    const REFUND_FOR_MISSING_BICYCLE = 1;
    const REFUND_FOR_CANCELLED_RACE  = 2;
}
