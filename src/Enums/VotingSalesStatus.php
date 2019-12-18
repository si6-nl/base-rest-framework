<?php

namespace Si6\Base\Enums;

/**
 * @method static static BEFORE_VOTE()
 * @method static static VOTING()
 * @method static static PENDING_OR_CANCELED()
 * @method static static FINISHED()
 */
class VotingSalesStatus extends Enum
{
    const BEFORE_VOTE         = 0;
    const VOTING              = 1;
    const PENDING_OR_CANCELED = 2;
    const FINISHED            = 3;
}
