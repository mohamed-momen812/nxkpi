<?php

namespace Database\Seeders;

use App\Enums\ChartsEnum;
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
        DB::table('dashboards')->delete();

        $charts = [ChartsEnum::COLUMN_GRAPH , ChartsEnum::LINE_GRAPH , ChartsEnum::SINGLE_KPI ,ChartsEnum::STACKED_KPI_GRAPH , ChartsEnum::MULTIPLE_KPI_SERIES, ChartsEnum::SINGLE_COLUMN_KPI ];

        $dashborads_id = Dashboard::pluck('id')->toArray();

        foreach(range(1,5) as $index)
        {
            DB::table('dashboards')->insert([
                'chart'     => $charts[array_rand($charts)],
                'dashboard_id'   => $dashborads_id[array_rand($dashborads_id)],
            ]);
        }
    }
}
