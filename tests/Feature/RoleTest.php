<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Foundations\Domain\Roles\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage roles,show roles,create roles,edit roles,delete roles']);
    }

    public function test_can_list_roles()
    {
        Role::factory()->count(3)->create();

        $response = $this->get(route('roles.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_role()
    {
        $permission = Permission::create(['name' => 'test permission', 'guard_name' => 'web']);
        
        $data = [
            'name' => 'New Role',
            'p_' . $permission->id => 'on',
            'can_access_panel' => 'on',
        ];

        $response = $this->post(route('roles.store'), $data);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', [
            'name' => 'New Role',
            'can_access_panel' => true,
        ]);
    }

    public function test_can_show_role()
    {
        $role = Role::factory()->create();

        $obfuscatedId = customEncoder($role->id);
        $response = $this->get(route('roles.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_update_role()
    {
        $role = Role::factory()->create();
        $permission = Permission::create(['name' => 'update permission', 'guard_name' => 'web']);

        $data = [
            'name' => 'Updated Role',
            'p_' . $permission->id => 'on',
            'can_access_panel' => 'on',
        ];

        $obfuscatedId = customEncoder($role->id);
        $response = $this->put(route('roles.update', $obfuscatedId), $data);

        $response->assertRedirect(route('roles.index')); // RoleController store/update redirects to index
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Role',
        ]);
    }

    public function test_can_delete_role()
    {
        $role = Role::factory()->create();

        $obfuscatedId = customEncoder($role->id);
        $response = $this->delete(route('roles.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseMissing('roles', [
            'id' => $role->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('roles.store'), []);

        $response->assertSessionHasErrors(['name', 'permissions']);
    }
}
