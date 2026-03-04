<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Permissions\Permission;
use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Foundations\Domain\Roles\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage permissions,show permissions,create permissions,edit permissions,delete permissions']);
    }

    public function test_can_list_permissions()
    {
        Permission::factory()->count(3)->create();

        $response = $this->get(route('permissions.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Permissions/Index')
            ->has('data')
        );
    }

    public function test_can_create_permission()
    {
        $data = [
            'name' => 'test permission',
        ];

        $response = $this->post(route('permissions.store'), $data);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', [
            'name' => 'test permission',
        ]);
    }

    public function test_can_edit_permission()
    {
        $permission = Permission::factory()->create();

        $response = $this->get(route('permissions.edit', customEncoder($permission->id)));

        $response->assertStatus(200);
        $response->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Permissions/CreateEdit')
            ->has('permission')
        );
    }

    public function test_can_update_permission()
    {
        $permission = Permission::factory()->create();

        $data = [
            'name' => 'updated permission',
        ];

        $obfuscatedId = customEncoder($permission->id);
        $response = $this->put(route('permissions.update', $obfuscatedId), $data);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'updated permission',
        ]);
    }

    public function test_can_delete_permission()
    {
        $permission = Permission::factory()->create();

        $obfuscatedId = customEncoder($permission->id);
        $response = $this->delete(route('permissions.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id
        ]);
    }

    public function test_cannot_delete_permission_in_use()
    {
        $permission = Permission::factory()->create();
        $role = Role::factory()->create();
        $role->givePermissionTo($permission);

        $obfuscatedId = customEncoder($permission->id);
        $response = $this->delete(route('permissions.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('permissions.store'), []);

        $response->assertSessionHasErrors(['name']);
    }
}
