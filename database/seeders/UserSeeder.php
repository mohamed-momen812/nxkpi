<?php

namespace Database\Seeders;

use App\Models\Group;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $groups = Group::all();

        DB::table('users')->delete();
        
        foreach( $groups as $group )
        {
            $group->users()->create([
                'first_name' => $faker->name(),
                'last_name' => $faker->name(),
                'email' => $faker->email(),
                'password' => Hash::make('11111111')
            ]);
        }
    }
}
