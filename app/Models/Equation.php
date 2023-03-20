<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equation extends Model
{
    use HasFactory;

    protected $fillable =["equat_body" , "kpi_id"];

    public function kpi(){
        return $this->belongsTo(Kpi::class , 'kpi_id');
    }
}
