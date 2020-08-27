<?php

namespace Si6\Base\Enums;

class OddsStatus extends Enum
{
    public const BEFORE_SALE       = 0;
    public const VOTING            = 1;
    public const SUSPEND_OR_CANCEL = 2;
    public const FINISH_SALE       = 3;
}
