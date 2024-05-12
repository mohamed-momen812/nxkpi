<?php

namespace App\Http\Resources;

use App\Models\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            // 'created_at'    => $this->created_at->format('d-m-y'),
            // 'updated_at'    => $this->updated_at->format('d-m-y'),
            // 'permissions'   => PermissionResource::collection($this->permissions),
            'permissions'   => $this->mapPermissions($this->permissions),
        ];
    }

    protected function mapPermissions($permissions)
    {
        $modules = Module::all();
    
        $mappedPermissions = [];
        
        foreach ($modules as $module) {
            $modulePermissions = $module->permissions()->pluck('permissions.id')->toArray();
            $mappedPermissions[$module->name] = $permissions->filter(function ($permission) use ($modulePermissions) {
                return in_array($permission->id, $modulePermissions);
            })->pluck('name')->toArray();
        }
        
        return $mappedPermissions;
        // $modules->map(function ($module) use ($permissions) {
        //     $module->permissions = $permissions->filter(function ($permission) use ($module) {
        //         return str_starts_with($permission->name, $module->name . '.');
        //     });
        // });
        // Initialize an empty array to store mapped permissions
        // $mappedPermissions = [];

        // // Loop through each permission to map them by module
        // foreach ($permissions as $permission) {
        //     // Extract the module name from the permission name
        //     $moduleName = explode('.', $permission->name)[0];
            
        //     // Append the permission to the module's array
        //     $mappedPermissions[$moduleName][] = $permission->only(['id', 'name']);
        // }

        // return $mappedPermissions;
    }
}
