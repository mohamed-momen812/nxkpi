<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->delete();

        foreach(range(1,5) as $index)
        {
            DB::table('groups')->insert([
                'name' => Str::random(6),
                'sort_order' => rand(1,5)
            ]);
        }
        
    }
}
