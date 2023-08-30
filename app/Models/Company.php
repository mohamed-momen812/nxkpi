<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name','logo','user_id','support_email','country','import_emails','export_emails','site_url'];

    public function user()
    {
        return $this->belongsTo( User::class , 'user_id');
    }
}
