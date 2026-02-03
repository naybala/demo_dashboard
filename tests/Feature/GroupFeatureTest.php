<?php
//SMALL_MODEL , MODEL , PLURAL_MODEL
namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\Groups\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GroupFeatureTest extends TestCase
{
    use DatabaseTransactions, TestingRequirement;

    const ROUTE = "/????";
    const TABLE = "????";

    //UnAuthorized Test
    public function test_prevent_non_logged_users_to_access_group_routes(): void
    {
        $group = Group::first()->toArray();;
        $groupList = $this->get(self::ROUTE);
        $groupCreate = $this->post(self::ROUTE);
        $groupUpdate = $this->put(self::ROUTE . "/" . $group['id']);
        $groupEdit = $this->get(self::ROUTE . "/" . $group['id'] . "/edit");
        $groupDelete = $this->delete(self::ROUTE . "/" . $group['id']);

        $groupList->assertStatus(302);
        $groupCreate->assertStatus(302);
        $groupUpdate->assertStatus(302);
        $groupEdit->assertStatus(302);
        $groupDelete->assertStatus(302);
    }

    //Store Validation Test
    public function test_group_cannot_store_without_name(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $request = $this->prepareData("");
        $response = $this->post(self::ROUTE, $request);
        $response->assertStatus(302);
    }


    //Store Process
    public function test_store_process_of_group(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfGroupBefore = Group::count();
        $request = $this->prepareData("Test Name");
        $this->post(self::ROUTE, $request);
        $totalNumberOfGroupAfter = Group::count();
        $this->assertEquals($totalNumberOfGroupBefore + 1, $totalNumberOfGroupAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($request));
    }

    //Listing Process
    public function test_list_of_group(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $response = $this->get(self::ROUTE);
        $response->assertStatus(200);
    }

    //Update Process
    public function test_update_process_of_group(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $oldData = Group::Create($this->prepareData("Test Name"));
        $totalNumberOfGroupBefore = Group::count();
        $updateData = $this->prepareData("Update Name");
        $this->put(self::ROUTE . "/" . customEncoder($oldData->id), $updateData);
        $totalNumberOfGroupAfter = Group::count();
        $this->assertEquals($totalNumberOfGroupBefore, $totalNumberOfGroupAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($updateData));
    }

    //Delete Validation
    public function test_cgroup_cannot_delete_without_id(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $deleteData = Group::first();
        $request = $this->prepareDataForDelete('');
        $response = $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $response->assertStatus(302);
    }

    //Delete Process
    public function test_delete_process_of_group(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfGroupBefore = Group::Count();
        $deleteData = Group::first();
        $request = $this->prepareDataForDelete($deleteData->id);
        $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $totalNumberOfGroupAfter = Group::Count();
        $this->assertEquals($totalNumberOfGroupBefore, $totalNumberOfGroupAfter + 1);
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
