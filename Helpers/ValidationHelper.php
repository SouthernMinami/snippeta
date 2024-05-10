<?php

namespace Helpers;

class ValidationHelper
{
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        // 値が整数かどうかを検証
        // filter_var()について ...  https://www.php.net/manual/en/filter.filters.validate.php
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range" => (int) $max]);

        if ($value === false)
            throw new \InvalidArgumentException("The provided value is not a valid integer.");

        return $value;
    }
}
