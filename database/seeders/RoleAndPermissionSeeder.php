<?php

namespace Database\Seeders;

use App\Models\User;
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
        DB::table('module_permission')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('role_has_permissions')->delete();

        $moduleCollection = collect([
            [
                'name' => 'Category',
                'display_name' => 'Category_module',
                'model_type' => 'App\Models\Category'
            ],[
                'name'  => 'Chart',
                'display_name' => 'Chart_module',
                'model_type' => 'App\Models\Chart'
            ],[
                'name'  => 'Company',
                'display_name' => 'Company_module',
                'model_type' => 'App\Models\Company'
            ],[
                'name'  => 'Kpi',
                'display_name' => 'Kpi_module',
                'model_type' => 'App\Models\Kpi'
            ],[
                'name' => 'User',
                'display_name' => 'User_module',
                'model_type' => 'App\Models\User'
            ],[
                'name' => 'Group',
                'display_name' => 'Group_module',
                'model_type' => 'App\Models\Group'
            ],[
                'name' => 'Setting',
                'display_name' => 'Setting_module',
                'model_type' => 'App\Models\Setting'
            ],[
                'name' => 'Dashboard',
                'display_name' => 'Dashboard_module',
                'model_type' => 'App\Models\Dashboard'
            ],[
                'name' => 'Entry',
                'display_name' => 'Entry_module',
                'model_type' => 'App\Models\Entry'
            ]
        ]);

        $moduleCollection->each(function ($item, $key) {
            $ids = [];
            $sids = [];

            $p_view = Permission::firstOrCreate([
                'name' => 'view_'.$item['name'],
                'display_name' => 'view_'.strtolower($item['name']),
                'guard_name' => 'sanctum',
            ]);
            $p_insert = Permission::firstOrCreate([
                'name' => 'create_'.$item['name'],
                'display_name' => 'create_'.strtolower($item['name']),
                'guard_name' => 'sanctum',
            ]);

            $p_update = Permission::firstOrCreate([
                'name' => 'update_'.$item['name'],
                'display_name' => 'update_'.strtolower($item['name']),
                'guard_name' => 'sanctum',
            ]);

            $p_update_status = Permission::firstOrCreate([
                'name' => 'update_status_'.$item['name'],
                'display_name' => 'update_status_'.strtolower($item['name']),
                'guard_name' => 'sanctum',
            ]);

            $p_delete = Permission::firstOrCreate([
                'name' => 'delete_'.$item['name'],
                'display_name' => 'delete_'.strtolower($item['name']),
                'guard_name' => 'sanctum',
            ]);

            $ids[] = Permission::where('display_name', 'view_' . strtolower($item['name']))->first()->id;
            $ids[] = Permission::where('display_name', 'create_' . strtolower($item['name']))->first()->id;
            $ids[] = Permission::where('display_name', 'update_' . strtolower($item['name']))->first()->id;
            $ids[] = Permission::where('display_name', 'update_status_' . strtolower($item['name']))->first()->id;
            $ids[] = Permission::where('display_name', 'delete_' . strtolower($item['name']))->first()->id;

            //insert model
            $module = \App\Models\Module::create($item);

            $mod = \App\Models\Module::where('name', $item['name'])->first();

            $mod->permissions()->attach($ids);

        });
        // //permissions
        // Permission::firstOrCreate(['name' => 'access-assigned-kpis', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'create-kpis', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'manage-users', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'full-access', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'enter-values', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'view-kpis', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'edit-kpis', 'guard_name' => 'sanctum']);
        // Permission::firstOrCreate(['name' => 'manage-settings', 'guard_name' => 'sanctum']);

        $ownerRole = Role::create(['name' => 'Owner', 'guard_name' => 'sanctum']);
        $userRole = Role::create(['name' => 'User', 'guard_name' => 'sanctum']);
        $managerRole = Role::create(['name' => 'Manager', 'guard_name' => 'sanctum']);
        $directorRole = Role::create(['name' => 'Director', 'guard_name' => 'sanctum']);
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'sanctum']);

        //give all permissions to owner
        $allPermissions = Permission::all();
    
        $ownerRole->syncPermissions($allPermissions);

        $user = User::where('type', 'owner')->first();
        if($user)
            $user->assignRole($ownerRole->name);
            
        //give all views permissions to manager    
        $managerPermissions = [];
        $moduleCollection->each(function ($item, $key) use (&$managerPermissions) {
            $managerPermissions[] = Permission::where('name', 'view_'.$item['name'])->first();
        });
        
        $managerRole->syncPermissions($managerPermissions);
        
        $managers = User::where('type', 'manager')->get();
        if(!empty($managers)){
            foreach($managers as $manager){
                $manager->assignRole($managerRole->name);
            }
        }

        //give some permissions to user
        $userPermissions = Permission::all();
        $userPermissions = $userPermissions->filter(function ($permission) {
            return $permission->name != 'view_Kpi' && $permission->name != 'view_Setting';
        });
        
        $userRole->syncPermissions($userPermissions);
        
        $users = User::where('type', 'user')->get();
        if(!empty($users)){
            foreach($users as $user){
                $user->assignRole($userRole->name);
            }
        }

        //give all permissions of category, kpi, entry, dashboard, chart modules to director role
        $allPermissions = Permission::all();
        $directorPermissions = $allPermissions->filter(function ($permission) {
            $moduleNames = ['Category', 'Kpi', 'Entry', 'Dashboard', 'Chart'];
            foreach ($moduleNames as $moduleName) {
                
                if (strpos($permission->name, 'view_' . $moduleName) === 0 ||
                    strpos($permission->name, 'create_' . $moduleName) === 0 ||
                    strpos($permission->name, 'update_' . $moduleName) === 0 ||
                    strpos($permission->name, 'update_status_' . $moduleName) === 0 ||
                    strpos($permission->name, 'delete_' . $moduleName) === 0) {
                    return true;
                }
            }
            return false;
        });
      
        $directorRole->syncPermissions($directorPermissions);
        
        $directors = User::where('type', 'director')->get();
        if(!empty($directors)){
            foreach($directors as $director){
                $director->assignRole($directorRole->name);
            }
        }
        
        //give all permissions to admin role except update and delete permissions
        $allPermissions = Permission::all();
        $adminPermissions = $allPermissions->reject(function ($permission) {
            return strpos($permission->name, 'update_') === 0 || strpos($permission->name, 'delete_') === 0;
        });

        $adminRole->syncPermissions($adminPermissions);

        $admins = User::where('type', 'admin')->get();
        if(!empty($admins)){
            foreach ($admins as $admin) {
                $admin->assignRole($adminRole->name);
            }
        }
        
    }
}
