<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
use App\Traits\SubscriptionTrait;
use Database\Seeders\ChartSeeder;
use Database\Seeders\FrequencySeeder;
use Database\Seeders\PlanFeatureSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Artisan;
use Rennokki\Plans\Models\PlanModel;

class TenantService
{
    use SubscriptionTrait;

    private $categoryRepo ;

    public function __construct(CategoryRepositoryInterface $categoryRepository) {
        $this->categoryRepo = $categoryRepository ;
    }

    public function intiateTenant(Tenant $tenant , array $userData)
    {
        $tenant->run(function () use ($tenant){
            //make seed for role and permission
            Artisan::call('tenants:seed', [
                '--tenants' => $tenant['id'],
                '--class'   => RoleAndPermissionSeeder::class,
            ]);

            //make seed for frequency
            Artisan::call('tenants:seed', [
                '--tenants' => $tenant['id'],
                '--class'   => FrequencySeeder::class,
            ]);

            //make_seed_for_charts
            Artisan::call('tenants:seed', [
                '--tenants' => $tenant['id'],
                '--class'   => ChartSeeder::class,
            ]);

            Artisan::call('storage:link');
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
        });

    }
}
