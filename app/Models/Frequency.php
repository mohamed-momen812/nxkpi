<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frequency extends Model
{
    use HasFactory;

    protected $fillable =[ 'name', 'created_at', 'updated_at'];

    public function kpis()
    {
        return $this->hasMany( Kpi::class );
    }
}
