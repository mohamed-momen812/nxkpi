<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('role_has_permissions')->delete();

        //permissions
        Permission::create(['name' => 'access-assigned-kpis']);
        Permission::create(['name' => 'create-kpis']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'full-access']);
        Permission::create(['name' => 'enter-values']);
        Permission::create(['name' => 'view-kpis']);
        Permission::create(['name' => 'edit-kpis']);
        Permission::create(['name' => 'manage-settings']);

        $ownerRole = Role::create(['name' => 'Owner']);
        $userRole = Role::create(['name' => 'User']);
        $managerRole = Role::create(['name' => 'Manager']);
        $directorRole = Role::create(['name' => 'Director']);
        $adminRole = Role::create(['name' => 'Admin']);

        //give all permissions to owner
        $allPermissions = Permission::all();
        foreach ($allPermissions as $permission){
            $ownerRole->givePermissionTo($permission->name);
        }

        $userRole->givePermissionTo([
            'access-assigned-kpis',
        ]);

        $managerRole->givePermissionTo([
            'create-kpis',
            'edit-kpis',
            'manage-users'
        ]);

        $directorRole->givePermissionTo([
            'view-kpis'
        ]);

        $adminRole->givePermissionTo([
            'full-access',
        ]);
    }
}
