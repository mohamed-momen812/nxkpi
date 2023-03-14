<?php

namespace App\Traits;

use Carbon\Carbon;

trait Helper
{

    public function calcYearlyQuarter(Carbon $date)
    {
        $month = $date->month ;
        switch(true){
            case ( $month == 1 || $month == 2 || $month == 3 ):
                $quarter = 1 ;
                break;
            case ( $month == 4 || $month == 5 || $month == 6 ) :
                $quarter = 2 ;
                break;
            case ( $month == 7 || $month == 8 || $month == 9 ) :
                $quarter = 3 ;
                break;
            case ( $month == 10 || $month == 11 || $month == 12 ) :
                $quarter = 4 ;
                break;
        }
        return $quarter ;
    }

}
