<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use BasicDashboard\Foundations\Domain\Users\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        $features = config('numbers.permissions');
        $permissions = ['manage','show','create','edit','delete'];
        foreach($features as $feature){
            foreach($permissions as $permission){
                Permission::firstOrCreate([
                    'name' => $permission . ' ' . $feature
                ]);
            }
        }

        $role = Role::firstOrCreate(['name' => 'super_admin'], ['can_access_panel'=>1]);
        $role->givePermissionTo(Permission::all());
        
    }
}
