<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DashboardSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            GroupSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            FrequencySeeder::class,
            KpiSeeder::class,
            EntrySeeder::class,
            DashboardSeeder::class,
            RoleAndPermissionSeeder::class,
            KpiUserSeeder::class,
            ChartSeeder::class,
            ChartsDashboardsSeeder::class,
            PlanFeatureSeeder::class
        ]);
    }
}
