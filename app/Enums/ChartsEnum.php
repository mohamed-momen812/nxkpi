<?php

namespace App\Enums;


enum ChartsEnum: string
{
    case COLUMN_GRAPH = "column_graph";
    case LINE_GRAPH = "line_graph";
    case SINGLE_KPI = "single_kpi";
    case STACKED_KPI_GRAPH = "stacked_kpi_graph";
    case MULTIPLE_KPI_SERIES = "multiple_kpi_series";
    case SINGLE_COLUMN_KPI = "single_column_kpi";
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
                'code'   => "column_graph",
                'title' => trans('chart.column_graph.title'),
                'description' => trans('chart.column_graph.description'),
                'logo' => '',
            ],
            static::LINE_GRAPH => [
                'value' => 'line graph',
                'code' => "line_graph",
                'title' => trans('chart.column_graph.title'),
                'description' => trans('chart.column_graph.description'),
                'logo' => '',
            ],
            static::SINGLE_KPI => [
                'value'  => 'single kpi',
                'code' => "single_kpi",
                'title' => trans('chart.column_graph.title'),
                'description' => trans('chart.column_graph.description'),
                'logo' => '',
            ],
            static::STACKED_KPI_GRAPH => [
                'value'  => 'stacked kpi graph',
                'code' => "stacked_kpi_graph",
                'title' => trans('chart.column_graph.title'),
                'description' => trans('chart.column_graph.description'),
                'logo' => '',
            ],
            static::MULTIPLE_KPI_SERIES => [
                'value'  => 'multiple kpi series',
                'code' => "multiple_kpi_series",
                'title' => trans('chart.column_graph.title'),
                'description' => trans('chart.column_graph.description'),
                'logo' => '',
            ],
            static::SINGLE_COLUMN_KPI => [
                'value' => 'single column kpi',
                'code'  => "single_column_kpi",
                'title' => trans('chart.column_graph.title'),
                'description' => trans('chart.column_graph.description'),
                'logo' => '',
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
