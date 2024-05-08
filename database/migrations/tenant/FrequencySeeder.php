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
        DB::table('frequencies')->insert(['name'=>'Daily', 'type'=>'daily']);
        DB::table('frequencies')->insert(['name'=>'Weekly', 'type'=>'weekly']); 
        DB::table('frequencies')->insert(['name'=>'Monthly', 'type'=>'monthly']);
        DB::table('frequencies')->insert(['name'=>'Quarterly', 'type'=>'quarterly']);
        DB::table('frequencies')->insert(['name'=>'Yearly', 'type'=>'yearly']);
    }
}
