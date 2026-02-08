<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Required for PermissionMiddleware - assuming same pattern as DailyIncome
        session(['permission_key' => 'manage categories,show categories,create categories,edit categories,delete categories']);
    }

    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_category()
    {
        $data = [
            'name' => 'Test Category',
            'name_other' => 'Test Category Other',
            'note' => 'Test note',
        ];

        $response = $this->post(route('categories.store'), $data);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    }

    public function test_can_show_category()
    {
        $category = Category::factory()->create();

        $obfuscatedId = customEncoder($category->id);
        $response = $this->get(route('categories.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Updated Category',
            'name_other' => 'Updated Category Other',
            'note' => 'Updated note',
        ];

        $obfuscatedId = customEncoder($category->id);
        $response = $this->put(route('categories.update', $obfuscatedId), $data);

        $response->assertRedirect(route('categories.show', $obfuscatedId));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $obfuscatedId = customEncoder($category->id);
        $response = $this->delete(route('categories.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertSoftDeleted('categories', [
            'id' => $category->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('categories.store'), []);

        $response->assertSessionHasErrors(['name', 'name_other']);
    }
}
