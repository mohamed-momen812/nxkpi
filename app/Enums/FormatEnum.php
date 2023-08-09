<?php

namespace App\Enums;

use function trans;

enum FormatEnum: string
{
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case INTEGER_PERCENTAGE = 'integer_percentage';
    case FLOAT_PERCENTAGE = 'float_percentage';
    case DOLLAR = 'dollar';
    // case SAR = 'sar';
    // case SECONDS = 'seconds';
    // case MINUTES = 'minutes';
    // case HOURS = 'hours';
    // case DAYS = 'days';
    // case WEEKS = 'weeks';
    // case MONTHS = 'months';
    // case QTRS = 'qtrs';
    // case YEARS = 'years';

    public function data(): array
    {
        return match($this) {
            self::INTEGER => [
                'code' => 0,
                'name' => trans(key: 'format.integer'),
                'value' => self::INTEGER->value,
                'display' => '1,234'
            ],

            self::FLOAT => [
                'code' => 1,
                'name' => trans(key: 'format.float'),
                'value' => self::FLOAT->value,
                'display' => '1,234.56'
            ],

            self::INTEGER_PERCENTAGE => [
                'code' => 2,
                'name' => trans(key: 'format.integer_percentage'),
                'value' => self::INTEGER_PERCENTAGE->value,
                'display' => '12%'
            ],

            self::FLOAT_PERCENTAGE => [
                'code' => 3,
                'name' => trans(key: 'format.float_percentage'),
                'value' => self::FLOAT_PERCENTAGE->value,
                'display' => '12.34%'
            ],

            self::DOLLAR => [
                'code' => 3,
                'name' => trans(key: 'format.dollar'),
                'value' => self::DOLLAR->value,
                'display' => '12$'
            ],
        };
    }

    public static function collection(): array
    {
        $result = [];

        foreach(self::cases() as $key => $record) {
            $result[$key] = $record->data();
        }

        return $result;
    }
}
