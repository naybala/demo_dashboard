<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Foundations\Domain\Units\Unit;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $unit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->unit = Unit::factory()->create();

        $this->actingAs($this->user);

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage own-products,show own-products,create own-products,edit own-products,delete own-products']);
    }

    public function test_can_list_own_products()
    {
        OwnProduct::factory()->count(3)->create([
            'category_id' => $this->category->id,
            'unit_id' => $this->unit->id,
        ]);

        $response = $this->get(route('own-products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_own_product()
    {
        $data = [
            'name' => 'Test Product',
            'category_id' => $this->category->id,
            'unit_id' => $this->unit->id,
            'price' => 100,
            'investment' => 60,
            'profit' => 40,
        ];

        $response = $this->post(route('own-products.store'), $data);

        $response->assertRedirect(route('own-products.index'));
        $this->assertDatabaseHas('own_products', [
            'name' => 'Test Product',
        ]);
    }

    public function test_can_show_own_product()
    {
        $ownProduct = OwnProduct::factory()->create([
            'category_id' => $this->category->id,
            'unit_id' => $this->unit->id,
        ]);

        $obfuscatedId = customEncoder($ownProduct->id);
        $response = $this->get(route('own-products.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_update_own_product()
    {
        $ownProduct = OwnProduct::factory()->create([
            'category_id' => $this->category->id,
            'unit_id' => $this->unit->id,
        ]);

        $data = [
            'name' => 'Updated Product',
            'category_id' => $this->category->id,
            'unit_id' => $this->unit->id,
            'price' => 200,
            'investment' => 120,
            'profit' => 80,
        ];

        $obfuscatedId = customEncoder($ownProduct->id);
        $response = $this->put(route('own-products.update', $obfuscatedId), $data);

        $response->assertRedirect(route('own-products.show', $obfuscatedId));
        $this->assertDatabaseHas('own_products', [
            'id' => $ownProduct->id,
            'name' => 'Updated Product',
        ]);
    }

    public function test_can_delete_own_product()
    {
        $ownProduct = OwnProduct::factory()->create([
            'category_id' => $this->category->id,
            'unit_id' => $this->unit->id,
        ]);

        $obfuscatedId = customEncoder($ownProduct->id);
        $response = $this->delete(route('own-products.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('own-products.index'));
        $this->assertSoftDeleted('own_products', [
            'id' => $ownProduct->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('own-products.store'), []);

        $response->assertSessionHasErrors(['name', 'category_id', 'unit_id', 'price', 'investment', 'profit']);
    }
}
