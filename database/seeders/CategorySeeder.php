<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = Factory::create();
        $users = User::all();

        DB::table('categories')->delete();
        
        foreach( $users as $user )
        {
            
            foreach( range(1,3) as $i )
            {
                $user->categories()->create([
                    'name' => $faker->jobTitle(),
                    'sort_order' => rand(1,10)
                ]);
            }
            
        }
    }
}
