<?php

namespace App\Enums;


enum ChartsEnum
{
    const COLUMN_GRAPH = 1;
    const LINE_GRAPH = 2;
    const SINGLE_KPI = 3;
    const STACKED_KPI_GRAPH = 4;
    const MULTIPLE_KPI_SERIES = 5;
    const SINGLE_COLUMN_KPI = 6;
    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public function data() : array
    {
        return match($this) {
            static::COLUMN_GRAPH =>[
                'value'  => 'column graph',
                'code'   => 1
            ],
            static::LINE_GRAPH => [
                'value' => 'line graph',
                'code' => 2
            ],
            static::SINGLE_KPI => [
                'value'  => 'single kpi',
                'code' => 3
            ],
            static::STACKED_KPI_GRAPH => [
                'value'  => 'stacked kpi graph',
                'code' => 4
            ],
            static::MULTIPLE_KPI_SERIES => [
                'value'  => 'multiple kpi series',
                'code' => 5
            ],
            static::SINGLE_COLUMN_KPI => [
                'value' => 'single column kpi',
                'code'  => 6
            ],
        };
    }

    public static function collection(): array
    {
        $result = [];

        foreach(self::cases() as $key => $day) {
            $result[$key] = $day->data();
        }

        return $result;
    }
}