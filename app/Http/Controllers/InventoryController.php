<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryMovement;
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
        $mov = InventoryMovement::query()->orderBydesc('moved_at')->simplePaginate(10);


        return view('inventory', compact('products', 'warehouses','mov'));
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
