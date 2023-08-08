<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'chart', 'user_id', 'kpi_id', 'created_at', 'updated_at'];

    public function kpis()
    {
        return $this->hasMany(Kpi::class , 'kpi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
