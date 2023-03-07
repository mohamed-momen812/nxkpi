<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' , 'description' , 'user_target' , 'sort_order' , 'user_id' , 'frequency_id' , 'category_id' , 'created_at' , 'updated_at'];
}
