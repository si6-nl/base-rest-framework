<?php

namespace Si6\Base\Utils;

use Illuminate\Support\Carbon;

class CalendarUtil
{
    /**
     * @param bool $reverse
     * @return array
     */
    public function months(bool $reverse = false)
    {
        $range = range(1, 12);

        if ($reverse) {
            $range = array_reverse($range);
        }

        return $range;
    }

    /**
     * @param $year
     * @param $month
     * @return array
     */
    public function dates($year, $month)
    {
        $dates = [];
        $month = "$year-$month";

        $start = Carbon::parse($month)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        return $dates;
    }
}
