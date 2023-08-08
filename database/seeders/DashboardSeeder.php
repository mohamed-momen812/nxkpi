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


        $users_id = User::pluck('id')->toArray() ;

        foreach(range(1,5) as $index){
            DB::table('dashboards')->insert([
                'name'      => Str::random(6),
                'user_id'   => $users_id[array_rand($users_id)],
            ]);
        }
    }
}
