<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }
}
