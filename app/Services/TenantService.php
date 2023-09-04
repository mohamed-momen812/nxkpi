<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;

class TenantService
{
    private $categoryRepo ;
    public function __construct(CategoryRepositoryInterface $categoryRepository) {
        $this->categoryRepo = $categoryRepository ;
    }
    public function intiateTenant(Tenant $tenant , array $userData)
    {
        $tenant->run(function () use ($tenant , $userData){
            $userData['id'] = $tenant->user->id;
            $user = User::create($userData);
//            $user->assignRole( "Owner" );
            $category = $this->categoryRepo->create([
                'name'      =>'Default',
                'user_id'   => $user->id ,
                'sort_order'=> 1
            ]);
            $company = Company::create([
                "user_id" => $user->id ,
                "support_email" => $user->email,
                "country" => "United State",
                "site_url" => $tenant->id . '.nxkpi.test' ,
            ]);
        });
    }
}
