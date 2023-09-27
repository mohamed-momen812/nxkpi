<?php

namespace App\Models;

use App\Enums\ChartsEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = ['name','user_id', 'kpi_id', 'created_at', 'updated_at'];

//    protected $casts = [
////        'charts' => 'json',
//        'charts'    => ChartsEnum::class,
//    ];
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function charts()
    {
        return $this->belongsToMany(Chart::class, 'charts_dashboards', 'dashboard_id', 'chart_id');
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class , 'kpi_id');
    }
}
