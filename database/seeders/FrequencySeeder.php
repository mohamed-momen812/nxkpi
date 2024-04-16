<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('frequencies')->delete();
        DB::table('frequencies')->insert(['name'=>'Daily']);
        DB::table('frequencies')->insert(['name'=>'Weekly']);
        DB::table('frequencies')->insert(['name'=>'Monthly']);
        DB::table('frequencies')->insert(['name'=>'Quarterly']);
        DB::table('frequencies')->insert(['name'=>'Yearly']);
    }
}
