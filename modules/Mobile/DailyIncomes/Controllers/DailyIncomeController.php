<?php

namespace BasicDashboard\Mobile\DailyIncomes\Controllers;

use App\Http\Controllers\Controller;
use BasicDashboard\Mobile\DailyIncomes\Services\DailyIncomeService;
use BasicDashboard\Mobile\DailyIncomes\Resources\DailyIncomeResource;
use BasicDashboard\Web\DailyIncomes\Validation\StoreDailyIncomeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Throwable;

class DailyIncomeController extends Controller
{
    public function __construct(
        private DailyIncomeService $dailyIncomeService,
        private ResponseFactory $responseFactory
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $dailyIncomeList = $this->dailyIncomeService->paginate($request->all());
            $dailyIncomeList = DailyIncomeResource::collection($dailyIncomeList)->response()->getData(true);
            return $this->responseFactory->sendSuccessResponse('Index Success', $dailyIncomeList);
        } catch (Throwable $e) {
            return $this->responseFactory->sendErrorResponse($e->getMessage());
        }
    }

    public function store(StoreDailyIncomeRequest $request): JsonResponse
    {
        try {
            $this->dailyIncomeService->store($request->all());
            return $this->responseFactory->sendSuccessResponse('Daily Income stored successfully');
        } catch (Throwable $e) {
            return $this->responseFactory->sendErrorResponse($e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $dailyIncome = $this->dailyIncomeService->findOrFail($id);
            $dailyIncome = new DailyIncomeResource($dailyIncome);
            $data = $dailyIncome->response()->getData(true)['data'];
            return $this->responseFactory->sendSuccessResponse('Show success', $data);
        } catch (Throwable $e) {
            return $this->responseFactory->sendErrorResponse($e->getMessage());
        }
    }
}
