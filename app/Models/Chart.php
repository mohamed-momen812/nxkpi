<?php

namespace App\Models;

use App\Enums\ChartsEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'charts'    => ChartsEnum::class ,
    ];
    public function dashboards()
    {
        return $this->belongsToMany(Dashboard::class, 'charts_dashboards', 'chart_id', 'dashboard_id');
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }
}
