<?php

namespace Database\Factories;

use App\Models\Kpi;
use App\Models\User;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use function MongoDB\BSON\toJSON;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    use Helper;
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
                $today = Carbon::today();
                $entry_date = $today->subDay(rand(1,30));
                $day = $entry_date->day ;
                $week = $entry_date->weekOfYear ;
                $month = $entry_date->month;
                $quarter = $this->calcYearlyQuarter($entry_date) ;
                $year = $entry_date->year;
            break;
            case "Weakly":
                $today = Carbon::today();
                $entry_date = $today->subWeek(rand(1,30));
                $day = $entry_date->day ;
                $week = $entry_date->weekOfYear ;
                $month = $entry_date->month;
                $quarter = $this->calcYearlyQuarter($entry_date) ;
                $year = $entry_date->year;
            break;
            case "Monthly":
                $today = Carbon::today();
                $entry_date = $today->subMonth(rand(1,30));
                $day = $entry_date->day ;
                $week = $entry_date->weekOfYear ;
                $month = $entry_date->month;
                $quarter = $this->calcYearlyQuarter($entry_date) ;
                $year = $entry_date->year;

                // $actual = array( $now->format('F Y') => 5000 , $now->subMonth()->format('F Y') => 6000 , $now->subMonth(3)->format('F Y') => 7000);
            break;
            case "Quarterly":
                $today = Carbon::today();
                $date = $today->subMonth(rand(1,60));
                $entry_date = $date ;
                $day = $entry_date->day ;
                $week = $entry_date->weekOfYear ;
                $month = $entry_date->month;
                $quarter = $this->calcYearlyQuarter($entry_date) ;
                $year = $entry_date->year;
                // $actual = array( "Quarter 1 ( ".$now->year." )" => 5000 , "Quarter 4 ( ".$now->subYear()->year." )" => 6000 , "Quarter 3 ( ".$now->subYear()->year." )" => 7000);
                break;
            case "Yearly":
                $today = Carbon::today();
                $date = $today->subYear(rand(1,20));
                $entry_date = $date;
                $day = $entry_date->day ;
                $week = $entry_date->weekOfYear ;
                $month = $entry_date->month;
                $quarter = $this->calcYearlyQuarter($entry_date) ;
                $year = $entry_date->year;
                break;
            default:
                $entry_date = null ;
        }
        return [
            'user_id' => $this->faker->randomElement( User::pluck('id')->toArray() ),
            'kpi_id' => $kpi_id,
            'entry_date' => $entry_date,
            'actual' => $this->faker->numberBetween(1000,10000),
            'target' => $kpi_target,
            'day' => $day ,
            'weekNo' => $week ,
            'month' => $month ,
            'quarter' => $quarter ,
            'year' => $year ,
        ];
    }
}
