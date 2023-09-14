<?php

namespace Database\Seeders;

use App\Models\Kpi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KpiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $kpis_id  = Kpi::pluck('id')->toArray();

        foreach ($users as $user){
            $user->kpis()->attach(array_values( array_rand( array_values($kpis_id) , 3) ) );
        }
    }
}
