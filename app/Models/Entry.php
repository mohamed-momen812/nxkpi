<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;


    protected $fillable=['user_id' , 'kpi_id' ,'entry_date' , 'acual' ,'target', 'notes'];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function kpi(){
        return $this->belongsTo(Kpi::class , 'kpi_id');
    }

    public function getEntryDateAttribute($date)
    {
        $date = new Carbon($date);
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
                $month = $date->format('f');
                switch($month){
                    case "January" || "February" || "March":
                        $entry_date = "Quarter 1 ( ". $date->format('Y')." ) " ;
                        break;
                    case "April" || "May" || "June" :
                        $entry_date = "Quarter 2 ( ". $date->format('Y')." ) " ;
                        break;
                    case "July" || "August" || "September" :
                        $entry_date = "Quarter 3 ( ". $date->format('Y')." ) " ;
                        break;
                    case "October" || "November" || "December" :
                        $entry_date = "Quarter 4 ( ". $date->format('Y')." ) ";
                        break;
                }
                return $entry_date;
                break;
            case "Yearly":
                return $date->format('Y');
                break;
            
        }
    }

}
