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
        $kpis  = Kpi::all();

        foreach ($users as $user){
            $randomKpis = $kpis->shuffle()->take(3);
            $user->kpis()->attach($randomKpis);
        }
    }
}
