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
            </div>
        </div>
    </div>
    <!-- Tabla de stock por producto y almacén -->
<h2 class="mt-4 mb-3">Stock Actual</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="bg-primary text-white">
            <tr>
                <th>Producto</th>
                <th>Almacén</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockProductAndWarehouse as $stock)
                <tr>
                    <td>{{ $stock->product->name }}</td>
                    <td>{{ $stock->warehouse->name }}</td>
                    <td>
                        @if($stock->stock <= 0)
                            <span class="badge bg-danger">{{ $stock->stock }}</span>
                        @else
                            <span class="badge bg-success">{{ $stock->stock }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr>
<!-- Filtrado para los movimientos-->
<h2 class="mt-4 mb-3">Filtrar Movimientos</h2> 
<form action="{{ route('inventory.index') }}" method="GET" class="mb-4"> 
    <div class="row"> 
        <div class="col-md-5"> 
            <label for="filter_product_id" class="form-label">Producto</label> 
            <select name="product_id" id="filter_product_id" class="form-select"> 
                <option value="">Todos los productos</option> @foreach ($products as $product) 
                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}> {{ $product->name }} </option> 
                @endforeach </select> 
            </div> 
        <div class="col-md-5"> 
            <label for="filter_warehouse_id" class="form-label">Almacén</label> 
            <select name="warehouse_id" id="filter_warehouse_id" class="form-select"> 
                <option value="">Todos los almacenes</option> 
                @foreach ($warehouses as $warehouse) 
                <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}> {{ $warehouse->name }} </option> 
                @endforeach </select> 
            </div> 
        <div class="col-md-2 d-flex align-items-end"> 
    <button type="submit" class="btn btn-secondary w-100">Filtrar</button> 
</div>
 </div> 
</form> 
<!-- Tabla de movimientos recientes -->
<h2 class="mt-4 mb-3">Movimientos Recientes</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Almacén</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Referencia</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->id }}</td>
                    <td>{{ $movement->product->name }}</td>
                    <td>{{ $movement->warehouse->name }}</td>
                    <td>
                        @if($movement->type == 'IN')
                            <span class="badge bg-success">ENTRADA</span>
                        @else
                            <span class="badge bg-danger">SALIDA</span>
                        @endif
                    </td>
                    <td>{{ $movement->quantity }}</td>
                    <td>{{ $movement->reference }}</td>
                    <td>
                        {{-- Corrección para evitar el error 'format() on null' --}}
                        @if($movement->created_at)
                            {{ $movement->created_at->format('d/m/Y H:i') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $movements->links() }}
</div>

</div>


@endsection
