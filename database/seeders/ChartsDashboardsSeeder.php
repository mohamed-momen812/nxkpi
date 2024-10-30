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
        // Fetch all chart and dashboard IDs
        $charts_id = Chart::pluck('id')->toArray();
        $dashboards_id = Dashboard::pluck('id')->toArray();

        foreach ($dashboards_id as $dashboard_id) {
            // Get a random chart ID from the charts array
            $randomChartKey = array_rand($charts_id);
            $randomChartId = $charts_id[$randomChartKey];

            // Insert into charts_dashboards
            DB::table('charts_dashboards')->insert([
                'chart_id' => $randomChartId,
                'dashboard_id' => $dashboard_id,
            ]);
        }
    }

}
