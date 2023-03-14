<?php

namespace App\Models;

use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory , Helper;

    protected $fillable = ['user_id' , 'kpi_id' ,'entry_date' , 'acual' ,'target', 'notes' ,'day' , 'weekNo' , 'month' , 'quarter' ,'year' ];

    protected $appends = ["range_date"];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function kpi(){
        return $this->belongsTo(Kpi::class , 'kpi_id');
    }

    public function getRangeDateAttribute()
    {
        $date = new Carbon($this->entry_date);
        $freq = $this->kpi()->first()->frequency->name ;
        switch ($freq) {
            case "Daily":
                return $date->format('d F Y');
            break;
            case "Weakly":
                // return ($date->format('d F Y'));
                return "week " . $date->weekOfYear . " ( " . $date->format('d F Y') . " )";
            break;
            case "Monthly":
                return $date->format('F Y');
                // $actual = array( $now->format('F Y') => 5000 , $now->subMonth()->format('F Y') => 6000 , $now->subMonth(3)->format('F Y') => 7000);
            break;
            case "Quarterly":
                $entry_date = "Quarter " . $this->calcYearlyQuarter($date) . " ( ". $date->format('Y')." ) " ;
//                $month = $date->month;
//
//                switch(true){
//                    case ( $month == 1 || $month == 2 || $month == 3):
//                        $entry_date = "Quarter 1 ( ". $date->format('Y')." ) " ;
//                        break;
//                    case ( $month == 4 || $month == 5 || $month == 6) :
//                        $entry_date = "Quarter 2 ( ". $date->format('Y')." ) " ;
//                        break;
//                    case ( $month == 7 || $month == 8 || $month == 9) :
//                        $entry_date = "Quarter 3 ( ". $date->format('Y')." ) " ;
//                        break;
//                    case ( $month == 10 || $month == 11 || $month == 12) :
//                        $entry_date = "Quarter 4 ( ". $date->format('Y')." ) ";
//                        break;
//                }
                return $entry_date;
                break;
            case "Yearly":
                return $date->format('Y');
                break;

        }
    }

}
