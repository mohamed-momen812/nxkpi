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
        Permission::firstOrCreate(['name' => 'access-assigned-kpis', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'create-kpis', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'manage-users', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'full-access', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'enter-values', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'view-kpis', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'edit-kpis', 'guard_name' => 'sanctum']);
        Permission::firstOrCreate(['name' => 'manage-settings', 'guard_name' => 'sanctum']);

        $ownerRole = Role::create(['name' => 'Owner', 'guard_name' => 'sanctum']);
        $userRole = Role::create(['name' => 'User', 'guard_name' => 'sanctum']);
        $managerRole = Role::create(['name' => 'Manager', 'guard_name' => 'sanctum']);
        $directorRole = Role::create(['name' => 'Director', 'guard_name' => 'sanctum']);
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'sanctum']);

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
