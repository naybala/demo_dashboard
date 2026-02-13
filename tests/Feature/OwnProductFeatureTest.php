<?php
//SMALL_MODEL , MODEL , PLURAL_MODEL
namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\OwnProduct\OwnProduct;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OwnProductFeatureTest extends TestCase
{
    use DatabaseTransactions, TestingRequirement;

    const ROUTE = "/????";
    const TABLE = "????";

    //UnAuthorized Test
    public function test_prevent_non_logged_users_to_access_ownProduct_routes(): void
    {
        $ownProduct = OwnProduct::first()->toArray();;
        $ownProductList = $this->get(self::ROUTE);
        $ownProductCreate = $this->post(self::ROUTE);
        $ownProductUpdate = $this->put(self::ROUTE . "/" . $ownProduct['id']);
        $ownProductEdit = $this->get(self::ROUTE . "/" . $ownProduct['id'] . "/edit");
        $ownProductDelete = $this->delete(self::ROUTE . "/" . $ownProduct['id']);

        $ownProductList->assertStatus(302);
        $ownProductCreate->assertStatus(302);
        $ownProductUpdate->assertStatus(302);
        $ownProductEdit->assertStatus(302);
        $ownProductDelete->assertStatus(302);
    }

    //Store Validation Test
    public function test_ownProduct_cannot_store_without_name(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $request = $this->prepareData("");
        $response = $this->post(self::ROUTE, $request);
        $response->assertStatus(302);
    }


    //Store Process
    public function test_store_process_of_ownProduct(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfOwnProductBefore = OwnProduct::count();
        $request = $this->prepareData("Test Name");
        $this->post(self::ROUTE, $request);
        $totalNumberOfOwnProductAfter = OwnProduct::count();
        $this->assertEquals($totalNumberOfOwnProductBefore + 1, $totalNumberOfOwnProductAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($request));
    }

    //Listing Process
    public function test_list_of_ownProduct(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $response = $this->get(self::ROUTE);
        $response->assertStatus(200);
    }

    //Update Process
    public function test_update_process_of_ownProduct(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $oldData = OwnProduct::Create($this->prepareData("Test Name"));
        $totalNumberOfOwnProductBefore = OwnProduct::count();
        $updateData = $this->prepareData("Update Name");
        $this->put(self::ROUTE . "/" . customEncoder($oldData->id), $updateData);
        $totalNumberOfOwnProductAfter = OwnProduct::count();
        $this->assertEquals($totalNumberOfOwnProductBefore, $totalNumberOfOwnProductAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($updateData));
    }

    //Delete Validation
    public function test_cownProduct_cannot_delete_without_id(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $deleteData = OwnProduct::first();
        $request = $this->prepareDataForDelete('');
        $response = $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $response->assertStatus(302);
    }

    //Delete Process
    public function test_delete_process_of_ownProduct(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfOwnProductBefore = OwnProduct::Count();
        $deleteData = OwnProduct::first();
        $request = $this->prepareDataForDelete($deleteData->id);
        $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $totalNumberOfOwnProductAfter = OwnProduct::Count();
        $this->assertEquals($totalNumberOfOwnProductBefore, $totalNumberOfOwnProductAfter + 1);
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
