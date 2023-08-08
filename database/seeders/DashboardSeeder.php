<?php

namespace Database\Seeders;

use App\Enums\ChartsEnum;
use App\Models\Kpi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardSeeder extends Seeder
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

        $users_id = User::pluck('id')->toArray() ;

        $kpis_id = Kpi::pluck('id')->toArray() ;

        foreach(range(1,5) as $index)
        {
            DB::table('dashboards')->insert([
                'name'      => Str::random(6),
                'chart'     => $charts[array_rand($charts)],
                'user_id'   => $users_id[array_rand($users_id)],
                'kpi_id'    => $kpis_id[array_rand($kpis_id)],
            ]);
        }
    }
}
