<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Units\Unit;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage units,show units,create units,edit units,delete units']);
    }

    public function test_can_list_units()
    {
        Unit::factory()->count(3)->create();

        $response = $this->get(route('units.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_unit()
    {
        $data = [
            'name' => 'Kilo',
            'note' => 'Kilogram',
        ];

        $response = $this->post(route('units.store'), $data);

        $response->assertRedirect(route('units.index'));
        $this->assertDatabaseHas('units', [
            'name' => 'Kilo',
        ]);
    }

    public function test_can_show_unit()
    {
        $unit = Unit::factory()->create();

        $obfuscatedId = customEncoder($unit->id);
        $response = $this->get(route('units.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_update_unit()
    {
        $unit = Unit::factory()->create();

        $data = [
            'name' => 'Updated Kilo',
            'note' => 'Updated note',
        ];

        $obfuscatedId = customEncoder($unit->id);
        $response = $this->put(route('units.update', $obfuscatedId), $data);

        $response->assertRedirect(route('units.show', $obfuscatedId));
        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'Updated Kilo',
        ]);
    }

    public function test_can_delete_unit()
    {
        $unit = Unit::factory()->create();

        $obfuscatedId = customEncoder($unit->id);
        $response = $this->delete(route('units.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('units.index'));
        $this->assertSoftDeleted('units', [
            'id' => $unit->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('units.store'), []);

        $response->assertSessionHasErrors(['name']);
    }
}
