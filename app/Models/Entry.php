<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;


    protected $fillable=['user_id' , 'kpi_id' , 'acual' , 'notes'];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function kpi(){
        return $this->belongsTo(Kpi::class , 'kpi_id');
    }
}
