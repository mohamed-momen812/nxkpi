<?php

namespace App\Models;

use App\Traits\KpiTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
//use App\Traits\HasTranslations;
class Kpi extends Model
{
    use HasFactory, KpiTrait , HasTranslations;

    public $translatable = ['name' , 'description'];
    protected $fillable = ['name' , 'description' , 'user_target' , 'sort_order' ,'format','direction','aggregated','target_calculated','icon','thresholds', 'equation' , 'user_id' , 'frequency_id' , 'category_id' , 'created_at' , 'updated_at'];

//    protected $with =['frequency'];

    protected $appends = ['result_equation'];

    // Aggregated values
    const AGGREGATED_SUM_TOTAL = 'sum_totals';
    const AGGREGATED_AVERAGE = 'average';

    // public function setEquationAttribute($v){
    //     $equation = Equation::create([
    //         'equat_body' => $v ,
    //         'kpi_id' => $this->attributes['id'] ,
    //     ]);
    //     preg_match_all('#\#(.*?)\##', $v, $match);
    //     $kpis_id = $match[1];

    //     foreach($kpis_id as $kpi){
    //         $equations_kpi = DB::table('equations_kpis')->insert([
    //             'equation_id' => $equation->id ,
    //             'kpi_id' => $kpi ,
    //         ]);
    //     }

    //     $this->attributes['equation'] = $v;
    // }
    public function getResultEquationAttribute()
    {
        $v = $this->equation ;

        // detect delemeted -> () : '#\((.*?)\)#'
        $s = preg_match_all('#\#(.*?)\##', $v, $match);
        $targets = array();
        foreach( $match[1] as $id ){
            $kpi = Kpi::find($id);
            if(!$kpi){
                return null;
            }
            $kpi_target = $kpi->user_target ;
            array_push($targets , $kpi_target);
        }
        // detect delemeter -> () : '/\((.*?)\)/'
        $new_equat = preg_replace_array( "/\#(.*?)\#/" , $targets , $v ) ;
        $result = eval("return $new_equat;");

        return $result;
    }

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function frequency()
    {
        return $this->belongsTo(Frequency::class , 'frequency_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class , 'kpi_user', 'kpi_id', 'user_id');
    }

    public function charts()
    {
        return $this->belongsToMany(Chart::class);
    }

    public function dashboards()
    {
        return $this->hasMany(Dashboard::class , 'kpi_id');
    }
}
