<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\ChartSeeder;
use Database\Seeders\FrequencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;

class TenantService
{
    private $categoryRepo ;
    public function __construct(CategoryRepositoryInterface $categoryRepository) {
        $this->categoryRepo = $categoryRepository ;
    }
    public function intiateTenant(Tenant $tenant , array $userData)
    {
        $tenant->run(function () use ($tenant){
            //make seed for role and permission
            \Artisan::call('tenants:seed', [
                '--tenants' => $tenant['id'],
                '--class'   => RoleAndPermissionSeeder::class,
            ]);

            //make seed for frequency
            \Artisan::call('tenants:seed', [
                '--tenants' => $tenant['id'],
                '--class'   => FrequencySeeder::class,
            ]);

            //make_seed_for_charts
            \Artisan::call('tenants:seed', [
                '--tenants' => $tenant['id'],
                '--class'   => ChartSeeder::class,
            ]);
        });
        $tenant->run(function () use ($tenant , $userData){

            $userData['id'] = $tenant->user->id;
            $user = User::create($userData);
            $user->assignRole( "Owner" );
            $category = $this->categoryRepo->create([
                'name'      =>'Default',
                'user_id'   => $user->id ,
                'sort_order'=> 1
            ]);
            $company = Company::create([
                "user_id" => $user->id ,
                "support_email" => $user->email,
                "country" => "United State",
                "site_url" => $user->company_domain . config('tenancy.custom_domain') ,
            ]);
        });

    }
}
