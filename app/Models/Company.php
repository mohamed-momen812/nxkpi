<?php

namespace App\Models;

use App\Traits\OurHasPlansTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rennokki\Plans\Traits\HasPlans;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use HasFactory;
    // use HasPlans;
    use OurHasPlansTrait;

    protected $fillable = [
        'name',
        'logo',
        'user_id',
        'support_email',
        'country',
        'import_emails',
        'export_emails',
        'site_url',
        'invoices_email',
        'invoice_address',
        'default_frequency_id',
        'start_finantial_year',
        'start_of_week'
    ];

    public function user()
    {
        return $this->belongsTo( User::class , 'user_id');
    }
}
