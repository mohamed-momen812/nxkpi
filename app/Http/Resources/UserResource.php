<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class  UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            "id" => $this->id ,
            "first_name" => $this->first_name ,
            "last_name" => $this->last_name ,
            "email" => $this->email ,
            "company_domain" => $this->company_domain,
            "company_url" => $this->company_domain . config('tenancy.custom_domain'),
            "type"  => $this->type ,
            'roles' => $this->rolesWithPermissions->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                ];
            }),
            "added_permissions"    => $this->permissions->map(function ($permission){
                return ['name' => $permission->name];
            }),
            "company" => new CompanyResource($this->company),
            "tenant"  => new TenantResource(tenant()),
            "created_at" => $this->created_at->format('d-m-y') ,
            "group" => new GroupResource($this->group) ,
        ];
    }
}
