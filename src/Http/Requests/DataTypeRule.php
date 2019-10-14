<?php

namespace Si6\Base\Http\Requests;

trait DataTypeRule
{
    protected function varchar(int $max = 255, int $min = 0)
    {
        // The length can be specified as a value upto 65,535 after MySQL 5.0.3.
        // But maximum row size limit (without BLOB and TEXT) is 65,535 bytes.
        // So, I think it's should be 255
        $max = $max > 255 ? 255 : $max;

        $rule = ['string', "max:$max"];

        if ($min <= $max) {
            $rule[] = "min:$min";
        }

        return $rule;
    }

    protected function text()
    {
        $max = (int)pow(2, 16) - 1;

        return ['string', "max:$max"];
    }

    protected function unsignedTinyInteger()
    {
        return $this->tinyInteger(true);
    }

    protected function tinyInteger($unsigned = false)
    {
        return $this->integerRule('tiny', $unsigned);
    }

    protected function unsignedSmallInteger()
    {
        return $this->smallInteger(true);
    }

    protected function smallInteger($unsigned = false)
    {
        return $this->integerRule('small', $unsigned);
    }

    protected function unsignedMediumInteger()
    {
        return $this->mediumInteger(true);
    }

    protected function mediumInteger($unsigned = false)
    {
        return $this->integerRule('medium', $unsigned);
    }

    protected function unsignedInteger()
    {
        return $this->integer(true);
    }

    protected function integer($unsigned = false)
    {
        return $this->integerRule('int', $unsigned);
    }

    protected function unsignedBigInteger()
    {
        return $this->bigInteger(true);
    }

    protected function bigInteger($unsigned = false)
    {
        return $this->integerRule('big', $unsigned);
    }

    protected function integerRule($type, $unsigned = false)
    {
        $lengths = [
            'tiny'   => [-128, 0, 127, 255],
            'small'  => [-32768, 0, 32767, 65535],
            'medium' => [-8388608, 0, 8388607, 16777215],
            'int'    => [-2147483648, 0, 2147483647, 4294967295],
            'big'    => [PHP_INT_MIN, 0, PHP_INT_MAX, PHP_INT_MAX],
        ];

        $length = $lengths[$type];

        $max = $unsigned ? $length[3] : $length[2];
        $min = $unsigned ? $length[1] : $length[0];

        return ['integer', "max:$max", "min:$min"];
    }

    protected function decimal($total = 8, $places = 2)
    {
        $max = (pow(10, $total) - 1) / pow(10, $places);
        $min = -$max;

        return ['numeric', "max:$max", "min:$min", "size:$total"];
    }

    protected function double()
    {
        return ['numeric', 'max:' . PHP_FLOAT_MAX, 'min:' . (-1) * PHP_FLOAT_MAX];
    }
}
