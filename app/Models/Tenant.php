<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;


    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
        ];
        // querying data inside the data column with where()
        // where('data->foo', 'bar')
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
