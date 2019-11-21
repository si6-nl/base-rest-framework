<?php

namespace Si6\Base\Enums;

/**
 * @method static static VALID()
 * @method static static VOTE_EXPIRED()
 * @method static static CAN_NOT_VOTE()
 */
class VotePlatformStatus extends Enum
{
    const VALID        = 0;
    const VOTE_EXPIRED = 1;
    const CAN_NOT_VOTE = 2;
}
