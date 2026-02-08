<?php

namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Foundations\Domain\Units\Unit;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyIncomeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $ownProduct;
    protected $unit;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->unit = Unit::factory()->create();
        $this->category = Category::factory()->create();
        $this->ownProduct = OwnProduct::factory()->create([
            'unit_id' => $this->unit->id,
            'category_id' => $this->category->id,
        ]);
        
        $this->actingAs($this->user);

        // Required for PermissionMiddleware
        session(['permission_key' => 'manage daily-incomes,show daily-incomes,create daily-incomes,edit daily-incomes,delete daily-incomes']);
    }

    public function test_can_list_daily_incomes()
    {
        DailyIncome::factory()->count(3)->create([
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->get(route('daily-incomes.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_create_daily_income()
    {
        $data = [
            'date' => now()->toDateString(),
            'name' => 'Test Sale',
            'amount' => 5,
            'own_product_id' => $this->ownProduct->id,
            'unit_id' => $this->unit->id,
            'price' => 100,
            'investment' => 60,
            'profit' => 40,
            'is_instant' => 1,
            'note' => 'Test note',
        ];

        $response = $this->post(route('daily-incomes.store'), $data);

        $response->assertRedirect(route('daily-incomes.index'));
        $this->assertDatabaseHas('daily_incomes', [
            'name' => 'Test Sale',
            'is_instant' => true,
        ]);
    }

    public function test_can_update_daily_income()
    {
        $dailyIncome = DailyIncome::factory()->create([
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);

        $data = [
            'date' => now()->toDateString(),
            'name' => 'Updated Sale',
            'amount' => 10,
            'own_product_id' => $this->ownProduct->id,
            'unit_id' => $this->unit->id,
            'price' => 200,
            'investment' => 120,
            'profit' => 80,
            'is_instant' => 0,
            'note' => 'Updated note',
        ];

        // We use customEncoder for the ID in the route because our Controller expects obfuscated IDs
        $obfuscatedId = customEncoder($dailyIncome->id);
        $response = $this->put(route('daily-incomes.update', $obfuscatedId), $data);

        $response->assertRedirect(route('daily-incomes.show', $obfuscatedId));
        $this->assertDatabaseHas('daily_incomes', [
            'id' => $dailyIncome->id,
            'name' => 'Updated Sale',
            'is_instant' => false,
        ]);
    }

    public function test_can_delete_daily_income()
    {
        $dailyIncome = DailyIncome::factory()->create([
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);

        $obfuscatedId = customEncoder($dailyIncome->id);
        $response = $this->delete(route('daily-incomes.destroy', $obfuscatedId), [
            'id' => $obfuscatedId
        ]);

        $response->assertRedirect(route('daily-incomes.index'));
        $this->assertSoftDeleted('daily_incomes', [
            'id' => $dailyIncome->id
        ]);
    }

    public function test_can_show_daily_income()
    {
        $dailyIncome = DailyIncome::factory()->create([
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);

        $obfuscatedId = customEncoder($dailyIncome->id);
        $response = $this->get(route('daily-incomes.show', $obfuscatedId));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_can_filter_daily_incomes_by_keyword()
    {
        DailyIncome::factory()->create([
            'name' => 'Specific Sale',
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);
        DailyIncome::factory()->create([
            'name' => 'Other Items',
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->get(route('daily-incomes.index', ['keyword' => 'Specific']));

        $response->assertStatus(200);
        $data = $response->viewData('data');
        $this->assertCount(1, $data['data']);
        $this->assertEquals('Specific Sale', $data['data'][0]['name']);
    }

    public function test_can_paginate_daily_incomes()
    {
        DailyIncome::factory()->count(15)->create([
            'own_product_id' => $this->ownProduct->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->get(route('daily-incomes.index', ['paginate' => 5]));

        $response->assertStatus(200);
        $data = $response->viewData('data');
        $this->assertCount(5, $data['data']);
        $this->assertEquals(15, $data['meta']['total']);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->post(route('daily-incomes.store'), []);

        $response->assertSessionHasErrors(['date', 'name', 'own_product_id', 'amount', 'unit_id', 'price', 'investment', 'profit']);
    }

    public function test_it_formats_numbers_in_resource()
    {
        $dailyIncome = DailyIncome::factory()->create([
            'amount' => 1000,
            'price' => 50000,
            'investment' => 30000,
            'profit' => 20000,
            'own_product_id' => $this->ownProduct->id,
        ]);

        $resource = new \BasicDashboard\Web\DailyIncomes\Resources\DailyIncomeResource($dailyIncome);
        $data = $resource->toArray(request());

        $this->assertEquals('1,000', $data['amount']);
        $this->assertEquals('50,000', $data['price']);
        $this->assertEquals('30,000', $data['investment']);
        $this->assertEquals('20,000', $data['profit']);
    }
}
