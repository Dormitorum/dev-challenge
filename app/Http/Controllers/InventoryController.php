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
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        $movs = InventoryMovement::with(['product', 'warehouse'])->paginate(10);

        $stk = InventoryMovement::select('product_id', 'warehouse_id')->selectRaw("SUM(CASE WHEN type='IN' THEN quantity ELSE -quantity END) as stock")
            ->groupBy('product_id', 'warehouse_id')
            ->get();

        return view('inventory', compact('products', 'warehouses', 'movs', 'stk'));
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
