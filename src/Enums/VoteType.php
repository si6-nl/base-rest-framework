<?php

namespace Si6\Base\Enums;

/**
 * @method static static WIN()
 * @method static static PLACE_SHOW()
 * @method static static EXACTA()
 * @method static static QUINELLA()
 * @method static static BRACKET_EXACTA()
 * @method static static BRACKET_QUINELLA()
 * @method static static TRIFECTA()
 * @method static static TRIO()
 * @method static static WIDE()
 */
class VoteType extends Enum
{
    const WIN              = 10;
    const PLACE_SHOW       = 20;
    const EXACTA           = 30;
    const QUINELLA         = 31;
    const BRACKET_EXACTA   = 40;
    const BRACKET_QUINELLA = 41;
    const TRIFECTA         = 50;
    const TRIO             = 51;
    const WIDE             = 60;

    public static function isQuinella($type)
    {
        return in_array($type, [
            self::QUINELLA,
            self::BRACKET_QUINELLA,
            self::TRIO,
        ]);
    }
}
