<?php

namespace Si6\Base\Utils;

use Exception;
use Illuminate\Support\Carbon;

trait DateTimeUtil
{
    public function getDateString($value, $oldFormat = 'Ymd')
    {
        /** @var Carbon $date */
        $date = $this->createFromFormat($oldFormat, $value);

        return  $date ? $date->toDateString() : $date;
    }

    protected function createFromFormat(string $format, $time)
    {
        try {
            return Carbon::createFromFormat($format, $time);
        } catch (Exception $exception) {
            return null;
        }
    }
}
