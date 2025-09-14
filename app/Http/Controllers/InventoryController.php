<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryMovement;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

public function index(Request $request)
{
    // Obtener los parámetros de filtrado
    $productId = $request->input('product_id');
    $warehouseId = $request->input('warehouse_id');

    $query = InventoryMovement::with(['product', 'warehouse'])
        ->orderBy('id', 'desc');

    if ($productId) {
        $query->where('product_id', $productId);
    }

    if ($warehouseId) {
        $query->where('warehouse_id', $warehouseId);
    }
    $movements = $query->paginate(5);
    $movements->appends(request()->query());

    $products = Product::all();
    $warehouses = Warehouse::all();

    //Query para calcular el stock
    $stockProductAndWarehouse = InventoryMovement::selectRaw("
        product_id,
        warehouse_id,
        SUM(CASE WHEN type ='IN' THEN quantity ELSE 0 END)-
        SUM(CASE WHEN type ='OUT' THEN quantity ELSE 0 END) AS stock"
    )
    ->groupBy('product_id', 'warehouse_id')
    ->with(['product', 'warehouse'])
    ->get();
    return view('inventory', compact('products', 'warehouses', 'movements', 'stockProductAndWarehouse'));
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

        $this->inventoryService->registerMovement(
            $request->product_id,
            $request->warehouse_id,
            $request->type,
            $request->quantity,
            $request->reference
        );

        return redirect()->back()->with('ok', 'Movimiento registrado exitosamente.');
    }
}