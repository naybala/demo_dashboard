<?php
//SMALL_MODEL , MODEL , PLURAL_MODEL
namespace Tests\Feature;

use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DailyIncomeFeatureTest extends TestCase
{
    use DatabaseTransactions, TestingRequirement;

    const ROUTE = "/????";
    const TABLE = "????";

    //UnAuthorized Test
    public function test_prevent_non_logged_users_to_access_dailyIncome_routes(): void
    {
        $dailyIncome = DailyIncome::first()->toArray();;
        $dailyIncomeList = $this->get(self::ROUTE);
        $dailyIncomeCreate = $this->post(self::ROUTE);
        $dailyIncomeUpdate = $this->put(self::ROUTE . "/" . $dailyIncome['id']);
        $dailyIncomeEdit = $this->get(self::ROUTE . "/" . $dailyIncome['id'] . "/edit");
        $dailyIncomeDelete = $this->delete(self::ROUTE . "/" . $dailyIncome['id']);

        $dailyIncomeList->assertStatus(302);
        $dailyIncomeCreate->assertStatus(302);
        $dailyIncomeUpdate->assertStatus(302);
        $dailyIncomeEdit->assertStatus(302);
        $dailyIncomeDelete->assertStatus(302);
    }

    //Store Validation Test
    public function test_dailyIncome_cannot_store_without_name(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $request = $this->prepareData("");
        $response = $this->post(self::ROUTE, $request);
        $response->assertStatus(302);
    }


    //Store Process
    public function test_store_process_of_dailyIncome(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfDailyIncomeBefore = DailyIncome::count();
        $request = $this->prepareData("Test Name");
        $this->post(self::ROUTE, $request);
        $totalNumberOfDailyIncomeAfter = DailyIncome::count();
        $this->assertEquals($totalNumberOfDailyIncomeBefore + 1, $totalNumberOfDailyIncomeAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($request));
    }

    //Listing Process
    public function test_list_of_dailyIncome(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $response = $this->get(self::ROUTE);
        $response->assertStatus(200);
    }

    //Update Process
    public function test_update_process_of_dailyIncome(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $oldData = DailyIncome::Create($this->prepareData("Test Name"));
        $totalNumberOfDailyIncomeBefore = DailyIncome::count();
        $updateData = $this->prepareData("Update Name");
        $this->put(self::ROUTE . "/" . customEncoder($oldData->id), $updateData);
        $totalNumberOfDailyIncomeAfter = DailyIncome::count();
        $this->assertEquals($totalNumberOfDailyIncomeBefore, $totalNumberOfDailyIncomeAfter);
        $this->assertDatabaseHas(self::TABLE, $this->prepareDataForCheck($updateData));
    }

    //Delete Validation
    public function test_cdailyIncome_cannot_delete_without_id(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $deleteData = DailyIncome::first();
        $request = $this->prepareDataForDelete('');
        $response = $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $response->assertStatus(302);
    }

    //Delete Process
    public function test_delete_process_of_dailyIncome(): void
    {
        $this->AuthenticateUserForCustomMiddleware();
        $totalNumberOfDailyIncomeBefore = DailyIncome::Count();
        $deleteData = DailyIncome::first();
        $request = $this->prepareDataForDelete($deleteData->id);
        $this->delete(self::ROUTE . '/' . $deleteData, $request);
        $totalNumberOfDailyIncomeAfter = DailyIncome::Count();
        $this->assertEquals($totalNumberOfDailyIncomeBefore, $totalNumberOfDailyIncomeAfter + 1);
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
