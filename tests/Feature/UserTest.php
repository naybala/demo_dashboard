<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Users\User;
use App\Enums\Common\Status;
use App\Enums\Users\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'user_type' => UserType::Administrator,
        ]);
        $this->actingAs($this->admin);

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage users,show users,create users,edit users,delete users']);
    }

    public function test_can_list_users()
    {
        User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_user()
    {
        $data = [
            'fullname' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'status' => Status::Active->value,
            'role_marked' => 'Staff',
            'user_type' => UserType::User->value,
            'phone_number' => '123456789',
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'fullname' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_can_show_user()
    {
        $user = User::factory()->create();

        $obfuscatedId = customEncoder($user->id);
        $response = $this->get(route('users.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();

        $data = [
            'fullname' => 'Jane Doe',
            'email' => 'jane@example.com',
            'status' => Status::Active->value,
            'role_marked' => 'Admin',
            'user_type' => UserType::Administrator->value,
            'phone_number' => '987654321',
        ];

        $obfuscatedId = customEncoder($user->id);
        $response = $this->put(route('users.update', $obfuscatedId), $data);

        $response->assertRedirect(route('users.show', $obfuscatedId));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fullname' => 'Jane Doe',
        ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $obfuscatedId = customEncoder($user->id);
        $response = $this->delete(route('users.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('users.index'));
        // Soft delete test might depend on implementation, checking if record is gone or marked
        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('users.store'), []);

        $response->assertSessionHasErrors(['fullname', 'email', 'password', 'status', 'role_marked', 'user_type']);
    }
}
