<?php

namespace Database\Seeders;

use App\Models\Chart;
use App\Models\Dashboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartsDashboardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $charts_id = Chart::pluck('id')->toArray();
        $dashboards_id = Dashboard::pluck('id')->toArray();

        foreach ($dashboards_id as $dashboard_id){
            DB::table('charts_dashboards')->insert([
                'chart_id'      => array_rand( array_values( $charts_id) ),
                'dashboard_id'  => $dashboard_id,
            ]);
        }

    }
}
