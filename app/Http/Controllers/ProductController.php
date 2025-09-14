<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\Paginator;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    // Index
    public function Index()
    {
        $prod_ = Product::query()->simplePaginate(15);

        return view('product.index',
                    ['producto'=>$prod_]
                    );
    }

    // View Product
    public function View(Request $request)
    {
        $id_ = $request->input('id');         
        $prod_ = Product::find($id_);
        
        $stockxwareh_ = InventoryMovement::where('product_id',$id_)
                    ->with('warehouse')
                    ->selectRaw('(SUM(if(type = ?, quantity, 0)) - SUM(if(type = ?, quantity, 0))) as stock',["IN","OUT"])
                    ->addselect('product_id', 'warehouse_id')
                    ->groupBy('product_id', 'warehouse_id')
                    ->get();

        return view('product.view',
                    [
                        'product_'=>$prod_,
                        'stockxwareh'=>$stockxwareh_
                    ]
                    );
    }

    // UPDATE
    public function update(Request $request)
    {
        $id_ = $request->input('id');         
        $prod_ = Product::find($id_);
    
        return view('product.update',
                    [
                        'producto'=>$prod_
                    ]
                    );
    }

    // Store
    //-------------------------
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name|max:255',
            //'sku' => 'required|exists:products,sku', ? SKU es unico ?
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->inventoryService->createProduct(
            $request->name,
            $request->sku,           
        );

        return redirect()->back()->with('ok', 'Producto creado exitosamente.');
    }

    // UPDATE
    //-------------------------
    public function storeupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'name' => 'required|unique:products,name|max:255',
            //'sku' => 'required|exists:products,sku'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->inventoryService->updateProduct(
            $request->id,
            $request->name,
            $request->sku,           
        );

        return redirect('/product/')->with('ok', 'Producto actualizado exitosamente.');
    }


    // Store
    //-------------------------
    public function storedelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'id' => 'required|not_in:inventorymovements,product_id',          
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->inventoryService->deleteProduct(
            $request->id          
        );

        return redirect('/product/')->with('ok', 'Producto eliminado exitosamente.');
    }

}