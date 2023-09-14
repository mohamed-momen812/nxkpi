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
        Permission::firstOrCreate(['name' => 'access-assigned-kpis']);
        Permission::firstOrCreate(['name' => 'create-kpis']);
        Permission::firstOrCreate(['name' => 'manage-users']);
        Permission::firstOrCreate(['name' => 'full-access']);
        Permission::firstOrCreate(['name' => 'enter-values']);
        Permission::firstOrCreate(['name' => 'view-kpis']);
        Permission::firstOrCreate(['name' => 'edit-kpis']);
        Permission::firstOrCreate(['name' => 'manage-settings']);

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
