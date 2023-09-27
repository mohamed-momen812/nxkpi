<?php

namespace Database\Seeders;

use App\Enums\ChartsEnum;
use App\Models\Chart;
use App\Models\Dashboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('charts')->delete();

//        $charts = [
//            ChartsEnum::COLUMN_GRAPH ,
//            ChartsEnum::LINE_GRAPH ,
//            ChartsEnum::SINGLE_KPI ,
//            ChartsEnum::STACKED_KPI_GRAPH ,
//            ChartsEnum::MULTIPLE_KPI_SERIES,
//            ChartsEnum::SINGLE_COLUMN_KPI
//        ];
        $charts = [
            "column_graph",
            "line_graph",
            "single_kpi",
            "stacked_kpi_graph",
            "multiple_kpi_series",
            "single_column_kpi",
            "trend_graph",
            "rag_column_graph",
            "pie_chart_with_a_single_kpi",
            "pie_chart_with_multiple_kpis",
            "gauge_with_a_target",
            "gauge_with_a_goal",
            "rag_gauge",
            "text_box",
        ];

        foreach ($charts as $chart){
            Chart::create([ 'type'  => $chart ]);
        }

    }
}
