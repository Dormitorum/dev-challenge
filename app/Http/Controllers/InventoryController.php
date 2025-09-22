<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    const DEFAULT_PER_PAGE = 2;
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $allowedPerPage = [2, 5, 10];

        $sortBy = $request->input('sortBy', 'moved_at');
        $sortDir = $request->input('sortDir', 'desc');
        $filters = $request->only(['product_id','warehouse_id','type']);
        $perPage = $request->input('perPage', self::DEFAULT_PER_PAGE);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = self::DEFAULT_PER_PAGE;
        }

        $products = Product::all();
        $warehouses = Warehouse::all();
        $movements = InventoryMovement::with(['product', 'warehouse'])
            ->applyFilters($filters)
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends(array_merge(['sortBy' => $sortBy, 'sortDir' => $sortDir, 'perPage' => $perPage], $filters));
        
        $stockPerWarehouse = InventoryMovement::stockPerWarehouse();
        $stockTotal        = InventoryMovement::stockTotalPerProducts();

        return view('inventory', compact(
            'products',
            'warehouses',
            'movements',
            'stockPerWarehouse',
            'stockTotal',
            'perPage',
            'allowedPerPage',
            'sortBy',
            'sortDir',
            'filters',
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:IN,OUT',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $this->inventoryService->registerMovement(
                $request->product_id,
                $request->warehouse_id,
                $request->type,
                $request->quantity,
                $request->reference
            );

            return redirect()->back()->with('ok', 'Movimiento registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }
    }
}
