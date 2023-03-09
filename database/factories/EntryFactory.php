<?php

namespace Database\Factories;

use App\Models\Kpi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $kpi_id = $this->faker->randomElement( Kpi::pluck('id')->toArray() );
        $kpi_target = Kpi::find($kpi_id)->user_target ;
        return [
            'user_id' => $this->faker->randomElement( User::pluck('id')->toArray() ),
            'kpi_id' => $kpi_id,
            'actual' => $this->faker->numberBetween(4000 , 10000),
            'target' => $kpi_target,
        ];
    }
}
