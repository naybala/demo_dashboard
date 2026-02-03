<?php
//SMALL_MODEL , MODEL , PLURAL_MODEL
namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\UserTrials\UserTrial;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTrialFeatureTest extends TestCase
{
    use DatabaseTransactions, TestingRequirement;

    const ROUTE = "/????";
    const TABLE = "????";

    //UnAuthorized Test
    public function test_prevent_non_logged_users_to_access_userTrial_routes(): void
    {
        $userTrial = UserTrial::first()->toArray();;
        $userTrialList = $this->get(self::ROUTE);
        $userTrialCreate = $this->post(self::ROUTE);
        $userTrialUpdate = $this->put(self::ROUTE . "/" . $userTrial['id']);
        $userTrialEdit = $this->get(self::ROUTE . "/" . $userTrial['id'] . "/edit");
        $userTrialDelete = $this->delete(self::ROUTE . "/" . $userTrial['id']);

        $userTrialList->assertStatus(302);
        $userTrialCreate->assertStatus(302);
        $userTrialUpdate->assertStatus(302);
        $userTrialEdit->assertStatus(302);
        $userTrialDelete->assertStatus(302);
    }

    //Store Validation Test
    public function test_userTrial_cannot_store_without_name(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $request = $this->prepareData("");
        $response = $this->post(self::ROUTE, $request);
        $response->assertStatus(302);
    }


    //Store Process
    public function test_store_process_of_userTrial(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfUserTrialBefore = UserTrial::count();
        $request = $this->prepareData("Test Name");
        $this->post(self::ROUTE, $request);
        $totalNumberOfUserTrialAfter = UserTrial::count();
        $this->assertEquals($totalNumberOfUserTrialBefore + 1, $totalNumberOfUserTrialAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($request));
    }

    //Listing Process
    public function test_list_of_userTrial(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $response = $this->get(self::ROUTE);
        $response->assertStatus(200);
    }

    //Update Process
    public function test_update_process_of_userTrial(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $oldData = UserTrial::Create($this->prepareData("Test Name"));
        $totalNumberOfUserTrialBefore = UserTrial::count();
        $updateData = $this->prepareData("Update Name");
        $this->put(self::ROUTE . "/" . customEncoder($oldData->id), $updateData);
        $totalNumberOfUserTrialAfter = UserTrial::count();
        $this->assertEquals($totalNumberOfUserTrialBefore, $totalNumberOfUserTrialAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($updateData));
    }

    //Delete Validation
    public function test_cuserTrial_cannot_delete_without_id(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $deleteData = UserTrial::first();
        $request = $this->prepareDataForDelete('');
        $response = $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $response->assertStatus(302);
    }

    //Delete Process
    public function test_delete_process_of_userTrial(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfUserTrialBefore = UserTrial::Count();
        $deleteData = UserTrial::first();
        $request = $this->prepareDataForDelete($deleteData->id);
        $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $totalNumberOfUserTrialAfter = UserTrial::Count();
        $this->assertEquals($totalNumberOfUserTrialBefore, $totalNumberOfUserTrialAfter + 1);
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
