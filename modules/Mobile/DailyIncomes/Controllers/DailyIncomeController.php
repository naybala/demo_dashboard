<?php

namespace BasicDashboard\Mobile\DailyIncomes\Controllers;

use App\Http\Controllers\Controller;
use BasicDashboard\Mobile\DailyIncomes\Services\DailyIncomeService;
use BasicDashboard\Mobile\DailyIncomes\Resources\DailyIncomeResource;
use BasicDashboard\Mobile\DailyIncomes\Validation\UpdateDailyIncomeRequest;
use BasicDashboard\Mobile\DailyIncomes\Validation\StoreDailyIncomeRequest;
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
           $decodedId = customDecoder($id);
           $dailyIncome = $this->dailyIncomeService->findOrFail($decodedId);
           $items = $this->dailyIncomeService->getByVoucherNo($dailyIncome);

           $formattedItems = DailyIncomeResource::collection($items)->response()->getData(true)['data'];
           $data = [
                'id' => $id,
                'date' => $dailyIncome->date,
                'is_instant' => $dailyIncome->dailyIncomeTotal?->is_instant ?? true,
                'note' => $dailyIncome->dailyIncomeTotal?->note,
                'items' => $formattedItems,
                'voucher_no' => $dailyIncome->dailyIncomeTotal?->voucher_no,
                'total_price' => number_format($dailyIncome->dailyIncomeTotal?->total_price ??0,2,'.',''),
                'total_investment' => number_format($dailyIncome->dailyIncomeTotal?->total_investment ??0,2,'.',''),
                'total_profit' => number_format($dailyIncome->dailyIncomeTotal?->total_profit ??0,2,'.',''),
            ];
            return $this->responseFactory->sendSuccessResponse('Show success', $data);
        } catch (Throwable $e) {
            return $this->responseFactory->sendErrorResponse($e->getMessage());
        }
    }

     public function update(UpdateDailyIncomeRequest $request, string $id): JsonResponse
    {
        try {
            $this->dailyIncomeService->update($request->validated(), customDecoder($id));
            return $this->responseFactory->sendSuccessResponse('Daily Income updated successfully');
        } catch (Throwable $e) {
            return $this->responseFactory->sendErrorResponse($e->getMessage());
        }
    }
}
