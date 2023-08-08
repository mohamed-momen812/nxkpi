<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id',  'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function charts()
    {
        return $this->hasMany(Chart::class);
    }
}
