<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse; 
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\InventoryMovement;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
		
		// Filtros GET
		$filters = [
			'product_id'   => $request->query('product_id'),
			'warehouse_id' => $request->query('warehouse_id'),
			'type'         => $request->query('type'),
			'from'         => $request->query('from'),
			'to'           => $request->query('to'),
		];

		$movements = InventoryMovement::with(['product','warehouse'])
			->when($filters['product_id'],   fn($q,$v) => $q->where('product_id', $v))
			->when($filters['warehouse_id'], fn($q,$v) => $q->where('warehouse_id', $v))
			->when($filters['type'],         fn($q,$v) => $q->where('type', $v))
			->when($filters['from'],         fn($q,$v) => $q->whereDate('moved_at', '>=', $v))
			->when($filters['to'],           fn($q,$v) => $q->whereDate('moved_at', '<=', $v))
			->orderByDesc('moved_at')
			->paginate(10)
			->appends($filters);

		// Stock por producto/almacén 
		$stockByWarehouse = InventoryMovement::selectRaw("
				product_id,
				warehouse_id,
				SUM(CASE WHEN type = 'IN'  THEN quantity ELSE 0 END)
			  - SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END) AS stock
			")
			->groupBy('product_id','warehouse_id')
			->get();

		// Total por producto 
		$stockTotals = InventoryMovement::selectRaw("
				product_id,
				SUM(CASE WHEN type = 'IN'  THEN quantity ELSE 0 END)
			  - SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END) AS stock
			")
			->groupBy('product_id')
			->get()
			->keyBy('product_id');

		return view('inventory', compact('products', 'warehouses', 'movements', 'filters', 'stockByWarehouse', 'stockTotals'));
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

		// Comprobar stock 
		$available = (int) InventoryMovement::query()
			->where('product_id', $request->product_id)
			->where('warehouse_id', $request->warehouse_id)
			->selectRaw("
				COALESCE(
					SUM(CASE WHEN type = 'IN'  THEN quantity ELSE 0 END)
				  - SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END), 0
				) AS stock
			")
			->value('stock');

		if ($request->type === 'OUT' && $request->quantity > $available) {
			return back()
				->withErrors(['quantity' => "Stock insuficiente. Disponible: $available unidades."])
				->withInput();
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
