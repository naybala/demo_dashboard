<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Foundations\Domain\Products\Product;
use BasicDashboard\Foundations\Domain\Categories\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);
        
        $this->category = Category::factory()->create();

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage products,show products,create products,edit products,delete products']);
        
        Storage::fake('public');
    }

    public function test_can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_product()
    {
        $data = [
            'name' => 'New Product',
            'name_other' => 'New Product Other',
            'price' => 100,
            'description' => 'Test description',
            'description_other' => 'Test description other',
            'photos' => [
                UploadedFile::fake()->image('product1.jpg'),
            ],
            'categories' => [$this->category->id],
            'is_banner' => true,
            'is_mini_banner' => true,
        ];

        $response = $this->post(route('products.store'), $data);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'is_banner' => 1,
            'is_mini_banner' => 1,
        ]);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $obfuscatedId = customEncoder($product->id);
        $response = $this->get(route('products.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'name_other' => 'Updated Product Other',
            'price' => 200,
            'description' => 'Updated description',
            'description_other' => 'Updated description other',
            'photos' => [
                UploadedFile::fake()->image('updated_product.jpg'),
            ],
            'categories' => [$this->category->id],
            'is_banner' => false,
            'is_mini_banner' => false,
        ];

        $obfuscatedId = customEncoder($product->id);
        $response = $this->put(route('products.update', $obfuscatedId), $data);

        $response->assertRedirect(route('products.show', $obfuscatedId));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'is_banner' => 0,
            'is_mini_banner' => 0,
        ]);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $obfuscatedId = customEncoder($product->id);
        $response = $this->delete(route('products.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('products.index'));
        // Product doesn't use SoftDeletes in the model shown (it doesn't have use SoftDeletes)
        // Wait, let me double check the Product model.
        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'name_other', 'price', 'photos', 'categories']);
    }
}
