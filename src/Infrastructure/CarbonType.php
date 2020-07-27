<?php

namespace Si6\Base\Infrastructure;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use Illuminate\Support\Carbon;

class CarbonType extends DateTimeType
{
    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return DateTime|DateTimeInterface|false|Carbon|mixed|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return Carbon::instance(parent::convertToPHPValue($value, $platform));
    }

}