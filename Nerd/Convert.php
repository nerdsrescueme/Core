<?php

namespace Nerd;

class Convert
{
    /**
     * Convert a percent value to decimal
     *
     * ## Usage
     *
     *   Convert::percentToDecimal('22.5%')
     *
     * @param    string          Percent value
     * @return   float
     */
    public static function percentToDecimal($percentage)
    {
        return floatval($percentage)/100;
    }

    /**
     * Convert a decimal to a percentage
     *
     * ## Usage
     *
     *   Convert::decimalToPercent(0.254) // Float
     *   Convert::decimalToPercent(23)    // Integer
     *
     * @param    integer|float    Decimal value
     * @return   string
     */
    public static function decimalToPercent($decimal)
    {
        if (!is_numeric($decimal)) {
            throw new \InvalidArgumentException('The value of argument 1 must be numeric');
        }

        return ($decimal * 100) . '%';
    }
}