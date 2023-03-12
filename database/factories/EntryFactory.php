<?php

namespace Database\Factories;

use App\Models\Kpi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use function MongoDB\BSON\toJSON;

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

        //detect the frequency of kpi to send acual entries based on it
        $kpi = Kpi::where('id' , $kpi_id)->with('frequency')->first();
        $kpi_freq = $kpi->frequency->name;
        switch ($kpi_freq) {
            case "Daily":
                $dt = Carbon::today();
                $actual = array($dt->toDateString() => 4000 , $dt->subDay()->toDateString() => 5000 , $dt->subDay(2)->toDateString() => 6000);
            break;
            case "Weakly":
                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d');
//                $weekEndDate = $now->endOfWeek()->format('Y-m-d ');
                $actual = array($weekStartDate => 9000 , $now->subWeek()->format('Y-m-d') => 7500 , $now->subWeek(1)->format('Y-m-d') => 8000);
            break;
            case "Monthly":
                $now = Carbon::today();

                $actual = array( $now->format('F Y') => 5000 , $now->subMonth()->format('F Y') => 6000 , $now->subMonth(3)->format('F Y') => 7000);
            break;
            case "Quarterly":
                $now = Carbon::today();

                $actual = array( "Quarter 1 ( ".$now->year." )" => 5000 , "Quarter 4 ( ".$now->subYear()->year." )" => 6000 , "Quarter 3 ( ".$now->subYear()->year." )" => 7000);
                break;
            case "Yearly":
                $now = Carbon::today();

                $actual = array( $now->year => 5000 , $now->subYear()->year => 6000 , $now->subYear(1)->year => 7000);
                break;
            default:
                $actual = null ;
        }
        return [
            'user_id' => $this->faker->randomElement( User::pluck('id')->toArray() ),
            'kpi_id' => $kpi_id,
            'actual' => json_encode( $actual ),
            'target' => $kpi_target,
        ];
    }
}
