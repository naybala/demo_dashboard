<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use BasicDashboard\Foundations\Domain\Products\Product;
use BasicDashboard\Foundations\Domain\Units\Unit;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
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
        session(['permission_key' => 'manage categories,show categories,create categories,edit categories,delete categories']);
    }

    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
        $response->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Categories/Index')
            ->has('data')
        );
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

        $response->assertRedirect(route('categories.index'));
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

    public function test_cannot_delete_category_with_products()
    {
        $category = Category::factory()->create();
        $product = clone Product::factory()->make(); // Need to make it, override relationships, and save or we can use the pivot correctly
        
        $product = Product::factory()->create();
        $product->categories()->attach($category->id); // Product has a Many-to-Many with Categories, not a `category_id` column

        $obfuscatedId = customEncoder($category->id);
        $response = $this->delete(route('categories.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id
        ]);
    }

    public function test_cannot_delete_category_with_own_products()
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();
        OwnProduct::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id, // Provide correct foreign key dependencies
        ]);

        $obfuscatedId = customEncoder($category->id);
        $response = $this->delete(route('categories.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('categories.store'), []);

        $response->assertSessionHasErrors(['name', 'name_other']);
    }
}
