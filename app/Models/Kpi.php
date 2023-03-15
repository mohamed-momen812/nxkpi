<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' , 'description' , 'user_target' , 'sort_order' ,'format','direction','aggregated','target_calculated','thresholds', 'user_id' , 'frequency_id' , 'category_id' , 'created_at' , 'updated_at'];

//    protected $with =['frequency'];

    public function category(){
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function frequency(){
        return $this->belongsTo(Frequency::class , 'frequency_id');
    }

    public function entries(){
        return $this->hasMany(Entry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
