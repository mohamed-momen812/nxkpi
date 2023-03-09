<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =['name' , 'user_id' ,'sort_order' ,'created_at' ,'updated_at'];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function kpis(){
        return $this->hasMany(Kpi::class);
    }

}
