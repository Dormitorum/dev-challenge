@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Gestión de inventario</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('ok'))
            <div class="alert alert-success">
                {{ session('ok') }}
            </div>
        @endif

        <div class="accordion" id="inventoryAccordion">
            <div class="card">
                <div class="card-header" id="manualMovementHeading">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#manualMovement" aria-expanded="true" aria-controls="manualMovement">
                            Movimiento manual
                            <i class="fas fa-chevron-down" id="manualMovementIcon"></i>
                        </button>
                    </h2>
                </div>

                <div id="manualMovement" class="collapse" aria-labelledby="manualMovementHeading" data-bs-parent="#inventoryAccordion">
                    <div class="card-body">
                        <form action="{{ route('inventory.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="product_id" class="form-label">Producto</label>
                                        <select name="product_id" id="product_id" class="form-select">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Tipo</label>
                                        <select name="type" id="type" class="form-select">
                                            <option value="IN">ENTRADA</option>
                                            <option value="OUT">SALIDA</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="reference" class="form-label">Referencia</label>
                                        <input type="text" name="reference" id="reference" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="warehouse_id" class="form-label">Almacén</label>
                                        <select name="warehouse_id" id="warehouse_id" class="form-select">
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Cantidad</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                <div class="card-header" id="manualMovementHeading">
                    
                        Movimientos  /  <a href="{{ route('product.index') }}">Productos</a>
                    
                </div>
                <div class="row p-3">
                <div class="row">
                    <div class="col-3">
                        <b>Fecha</b>
                    </div>
                    <div class="col-4">
                        <b>Producto</b>
                    </div>
                    <div class="col-2">
                        <b>SKU</b>
                    </div>
                    <div class="col-1">
                        <b>Cantidad</b>
                    </div>
                    <div class="col-2">
                        <b>Warehouse</b>
                    </div>
                </div>
                @foreach ($mov as $prod)
                <div class="row">
                    <div class="col-3">                        
                        {{ date('d M Y h:m A', strtotime($prod->moved_at)) }}
                    </div>
                    <div class="col-4">                       
                        <a href="{{ route('product.view',['id'=>$prod->product_id]) }}">{{ $prod->product->name }}</a>
                    </div>
                    <div class="col-2">
                        {{ $prod->product->sku }}
                    </div>
                    <div class="col-1 text-right">
                        @if ($prod->type == "OUT") 
                            - 
                        @endif
                        {{ $prod->quantity }}
                    </div>
                    <div class="col-2">
                        {{ $prod->warehouse->code }}  {{ $prod->warehouse->name }} 
                    </div>                               
                </div>            
                @endforeach  
            
                <div class="row p-3">
                    {{ $mov->links() }}
                </div>                

                </div>
            </div>
        
                </div>
            </div>
        </div>
    </div>
@endsection
