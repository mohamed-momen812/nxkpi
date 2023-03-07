<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' , 'display_format' , 'is_percentage' , 'entry_format' , 'kpi_id' , 'created_at' , 'updated_at'];
}
