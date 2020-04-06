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

    protected static $text = [
        self::WIN              => '単勝',
        self::PLACE_SHOW       => '複勝',
        self::EXACTA           => '２車単',
        self::QUINELLA         => '２車複',
        self::BRACKET_EXACTA   => '２枠単',
        self::BRACKET_QUINELLA => '２枠複',
        self::TRIFECTA         => '３連単',
        self::TRIO             => '３連複',
        self::WIDE             => 'ワイド',
    ];

    public static function getText($value)
    {
        return self::$text[$value] ?? '';
    }

    public static function getSign($type)
    {
        $equals    = [self::QUINELLA, self::BRACKET_QUINELLA, self::TRIO, self::WIDE];
        $negatives = [self::BRACKET_EXACTA, self::EXACTA, self::TRIFECTA];

        if (in_array($type, $equals)) {
            return '=';
        }

        if (in_array($type, $negatives)) {
            return '-';
        }

        return '';
    }

    public static function getOddsSign($item)
    {
        $item = (array)$item;

        $sign = self::getSign($item['vote_type']);

        $positions = [$item['position_1']];

        if (!empty($item['position_2'])) {
            $positions[] = $item['position_2'];
        }

        if (!empty($item['position_3'])) {
            $positions[] = $item['position_3'];
        }

        return implode($sign, $positions);
    }

    public static function isQuinella($type)
    {
        return in_array(
            $type,
            [
                self::QUINELLA,
                self::BRACKET_QUINELLA,
                self::TRIO,
            ]
        );
    }

    public static function isRequireSecondPosition($type)
    {
        return in_array(
            $type,
            [
                VoteType::EXACTA,
                VoteType::QUINELLA,
                VoteType::BRACKET_EXACTA,
                VoteType::BRACKET_QUINELLA,
                VoteType::TRIFECTA,
                VoteType::TRIO,
                VoteType::WIDE,
            ]
        );
    }

    public static function isRequireThirdPosition($type)
    {
        return in_array(
            $type,
            [
                VoteType::TRIFECTA,
                VoteType::TRIO,
            ]
        );
    }
}
