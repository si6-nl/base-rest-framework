<?php

namespace Si6\Base\Utils;

use Exception;
use Illuminate\Support\Carbon;

trait DateTimeUtil
{
    /**
     * @param $value
     * @param string $oldFormat
     * @return Carbon|string
     */
    public function getDateString($value, $oldFormat = 'Ymd')
    {
        /** @var Carbon $date */
        $date = $this->createFromFormat($oldFormat, $value);

        return $date ? $date->toDateString() : $date;
    }

    /**
     * @param string $format
     * @param $time
     * @return \Carbon\Carbon|null
     */
    public function createFromFormat(string $format, $time)
    {
        try {
            return Carbon::createFromFormat($format, $time);
        } catch (Exception $exception) {
            return null;
        }
    }
}
