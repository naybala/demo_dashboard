<?php

namespace BasicDashboard\Web\StockTransactions\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\StockTransactions\Services\StockTransactionService;
use BasicDashboard\Web\StockTransactions\Resources\StockTransactionResource;
use BasicDashboard\Web\Warehouses\Services\WarehouseService;
use BasicDashboard\Web\Warehouses\Resources\WarehouseResource;
use BasicDashboard\Web\OwnProducts\Services\OwnProductService;
use BasicDashboard\Web\StockTransactions\Validation\StoreStockTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\WarningException;
use Throwable;
use Inertia\Inertia;
use Inertia\Response;

class StockTransactionController extends BaseController
{
    const VIEW = 'StockTransactions/Index';
    const ROUTE = 'stock-transactions';
    const LANG_PATH = "stockTransaction.stockTransaction";

    public function __construct(
        private StockTransactionService $stockTransactionService,
        private WarehouseService $warehouseService,
        private OwnProductService $ownProductService
    ) {
    }

    public function index(Request $request): Response
    {
        $transactions = $this->stockTransactionService->paginate($request->all());
        $transactions = StockTransactionResource::collection($transactions)->response()->getData(true);

        $warehouses = $this->warehouseService->all();
        $warehouses = WarehouseResource::collection($warehouses)->response()->getData(true)['data'];

        $products = $this->ownProductService->all();
        $productsMapped = $products->map(function ($p) {
            return [
                'id' => customEncoder($p->id),
                'name' => $p->name,
                'unit' => $p->unit?->name ?? '',
            ];
        });

        return Inertia::render(self::VIEW, array_merge($transactions, [
            'warehouses' => $warehouses,
            'products' => $productsMapped,
            'filters' => $request->only(['keyword', 'warehouse_id', 'type'])
        ]));
    }

    public function store(StoreStockTransactionRequest $request): RedirectResponse
    {
        try {
            $this->stockTransactionService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_created'));
        } catch (WarningException $e) {
            return back()->with('error', __($e->getMessage()));
        } catch (Throwable $e) {
            $this->LogError("StockTransaction store failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }
}
