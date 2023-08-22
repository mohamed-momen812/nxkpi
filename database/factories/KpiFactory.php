<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Frequency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kpi>
 */
class KpiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(6),
            'user_target' => $this->faker->numberBetween(1000,10000),
            'sort_order' => rand(1,100),
            'format' => 'integer_float' ,
            'direction' => 'up' ,
            'aggregated' => 'Sum Total',
            'target_calculated' => false ,
            'icon'=> 'target',
            'thresholds' => null ,
            'user_id' => $this->faker->randomElement( User::pluck('id')->toArray() ),
            'frequency_id' => $this->faker->randomElement( Frequency::pluck('id')->toArray() ),
            'category_id' => $this->faker->randomElement( Category::pluck('id')->toArray() ),
        ];
    }
}
