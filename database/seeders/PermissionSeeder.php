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
                Permission::create([
                    'name' => $permission . ' ' . $feature
                ]);
            }
        }

        $role = Role::create(['name' => 'super_admin','can_access_panel'=>1]);
        $role->givePermissionTo(Permission::all());

        $agent = Role::create(['name' => 'agent','can_access_panel'=>1]);
        $agent->givePermissionTo(Permission::all());

        $approve = Role::create(['name' => 'Approve','can_access_panel'=>1]);
        $approve->givePermissionTo(Permission::all());

        $audit = Role::create(['name' => 'Audit','can_access_panel'=>1]);
        $audit->givePermissionTo(Permission::all());

        $dataEntry = Role::create(['name' => 'Data Indry (Lucky Realty., Ltd.)','can_access_panel'=>1]);
        $dataEntry->givePermissionTo(Permission::all());

        $bankApproval = Role::create(['name' => 'Bank Approvals','can_access_panel'=>1]);
        $bankApproval->givePermissionTo(Permission::all());

        $bankCo = Role::create(['name' => 'Bank CO','can_access_panel'=>1]);
        $bankCo->givePermissionTo(Permission::all());

        $adBank = Role::create(['name' => 'Administration (Bank)','can_access_panel'=>1]);
        $adBank->givePermissionTo(Permission::all());

        $verify = Role::create(['name' => 'Verify','can_access_panel'=>1]);
        $verify->givePermissionTo(Permission::all());

        $bankAdmin = Role::create(['name' => 'Bank Admin','can_access_panel'=>1]);
        $bankAdmin->givePermissionTo(Permission::all());

        $developer = Role::create(['name' => 'Developer','can_access_panel'=>1]);
        $developer->givePermissionTo(Permission::all());
        
    }
}
