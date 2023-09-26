<?php

namespace App\Models;

use App\Enums\ChartsEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'charts','user_id', 'kpi_id', 'created_at', 'updated_at'];

    protected $casts = [
//        'charts' => 'json',
        'charts'    => ChartsEnum::class,
    ];
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function charts()
    {
        return $this->hasMany(Chart::class);
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class , 'kpi_id');
    }
}
