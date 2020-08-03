<?php

namespace Si6\Base\Enums;

/**
 * @method static static JAPANESE()
 * @method static static ENGLISH()
 */
class Language extends Enum
{
    const JAPANESE = 'ja';
    const ENGLISH  = 'en';

    public static function getOrderByRaw($language)
    {
        $original = collect(["'" . self::JAPANESE . "'", "'" . self::ENGLISH . "'"]);

        return $original->filter(
            function ($item) use ($language) {
                return $item !== "'$language'";
            }
        )->prepend("'$language'")->implode(',');
    }
}
