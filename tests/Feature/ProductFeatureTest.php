<?php
//SMALL_MODEL , MODEL , PLURAL_MODEL
namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Products\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use DatabaseTransactions, TestingRequirement;

    const ROUTE = "/????";
    const TABLE = "????";

    //UnAuthorized Test
    public function test_prevent_non_logged_users_to_access_product_routes(): void
    {
        $product = Product::first()->toArray();;
        $productList = $this->get(self::ROUTE);
        $productCreate = $this->post(self::ROUTE);
        $productUpdate = $this->put(self::ROUTE . "/" . $product['id']);
        $productEdit = $this->get(self::ROUTE . "/" . $product['id'] . "/edit");
        $productDelete = $this->delete(self::ROUTE . "/" . $product['id']);

        $productList->assertStatus(302);
        $productCreate->assertStatus(302);
        $productUpdate->assertStatus(302);
        $productEdit->assertStatus(302);
        $productDelete->assertStatus(302);
    }

    //Store Validation Test
    public function test_product_cannot_store_without_name(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $request = $this->prepareData("");
        $response = $this->post(self::ROUTE, $request);
        $response->assertStatus(302);
    }


    //Store Process
    public function test_store_process_of_product(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfProductBefore = Product::count();
        $request = $this->prepareData("Test Name");
        $this->post(self::ROUTE, $request);
        $totalNumberOfProductAfter = Product::count();
        $this->assertEquals($totalNumberOfProductBefore + 1, $totalNumberOfProductAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($request));
    }

    //Listing Process
    public function test_list_of_product(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $response = $this->get(self::ROUTE);
        $response->assertStatus(200);
    }

    //Update Process
    public function test_update_process_of_product(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $oldData = Product::Create($this->prepareData("Test Name"));
        $totalNumberOfProductBefore = Product::count();
        $updateData = $this->prepareData("Update Name");
        $this->put(self::ROUTE . "/" . customEncoder($oldData->id), $updateData);
        $totalNumberOfProductAfter = Product::count();
        $this->assertEquals($totalNumberOfProductBefore, $totalNumberOfProductAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($updateData));
    }

    //Delete Validation
    public function test_cproduct_cannot_delete_without_id(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $deleteData = Product::first();
        $request = $this->prepareDataForDelete('');
        $response = $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $response->assertStatus(302);
    }

    //Delete Process
    public function test_delete_process_of_product(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfProductBefore = Product::Count();
        $deleteData = Product::first();
        $request = $this->prepareDataForDelete($deleteData->id);
        $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $totalNumberOfProductAfter = Product::Count();
        $this->assertEquals($totalNumberOfProductBefore, $totalNumberOfProductAfter + 1);
    }

    //Private Section
    private function prepareData(string $param1): array
    {
        return [
            "name" => $param1,
        ];
    }

    private function prepareDataForCheck(array $data): array
    {
        return [
            'name' => isset($data['name']) ? $data['name'] : '',
        ];
    }

    private function prepareDataForDelete(string $id): array
    {
        return [
            'id' => $id != '' ? customEncoder($id) : '',
        ];
    }

}
