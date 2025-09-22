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

        <div class="row">
            <div class="col-md-12 mb-4">
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
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5>Movimientos de Inventario</h5>
                    </div>
                    <div class="card ">
                        <div class="accordion" id="filterMovementsAccordion">
                            <div id="filterMovementsHeading">
                                <button class="btn btn-link btn-block text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#filterMovements" aria-expanded="true" aria-controls="filterMovements">
                                    🔎 Filtros
                                </button>
                            </div>

                            <div id="filterMovements" class="collapse" aria-labelledby="filterMovementsHeading" data-bs-parent="#filterMovementsAccordion">
                            
                                <div class="card-body">
                                    <form action="{{ route('inventory.index') }}" method="GET" class="form-row">

                                        <input type="hidden" name="perPage" value="{{ $perPage }}">
                                        <input type="hidden" name="sortBy" value="{{ $sortBy }}">
                                        <input type="hidden" name="sortDir" value="{{ $sortDir }}">
                                        <div class="form-group col-md-3">
                                            <label for="product_id">Producto</label>
                                            <select name="product_id" id="product_id" class="form-control">
                                                <option value="">-- Todos --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                        {{ (isset($filters['product_id']) && $filters['product_id']==$product->id) ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="warehouse_id">Almacén</label>
                                            <select name="warehouse_id" id="warehouse_id" class="form-control">
                                                <option value="">-- Todos --</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        {{ (isset($filters['warehouse_id']) && $filters['warehouse_id']==$warehouse->id) ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="type">Tipo</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="">-- Todos --</option>
                                                <option value="IN" {{ (isset($filters['type']) && $filters['type']=='IN')?'selected':'' }}>Entrada</option>
                                                <option value="OUT" {{ (isset($filters['type']) && $filters['type']=='OUT')?'selected':'' }}>Salida</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary">Aplicar</button>
                                            <a href="{{ route('inventory.index', ['sortBy' => $sortBy,'sortDir' => $sortDir,'perPage' => $perPage]) }}" class="btn btn-secondary">Limpiar</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive"> 
                        <table class="table table-striped">
                        <thead class="thead-light">
                                <tr>
                                    @include('partials.inventory-table-header', ['sortBy' => $sortBy, 'sortDir' => $sortDir])
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($movements as $inventoryMovement)
                                    <tr>
                                        <td>{{ $inventoryMovement->moved_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $inventoryMovement->warehouse->name }}</td>
                                        <td>{{ $inventoryMovement->product->name }}</td>
                                        <td><span class="badge badge-{{ $inventoryMovement->type == 'IN' ? 'success' : 'danger' }}">
                                            {{ $inventoryMovement->type === 'IN' ? 'ENTRADA' : 'SALIDA' }}</td>
                                        <td>{{ $inventoryMovement->quantity }}</td>
                                        <td>{{ $inventoryMovement->reference }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($movementTableColumns) }}">No hay movimientos de inventario.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-md-center align-items-center">
                            <div class="col-md-9">
                                {{ $movements->links() }}
                            </div>
                            <div class="col-md-3 justify-content-md-end d-flex">
                                <form action="{{ route('inventory.index') }}" method="GET" class="form-row">
                                    <input type="hidden" name="sortBy" value="{{ $sortBy }}">
                                    <input type="hidden" name="sortDir" value="{{ $sortDir }}">    
                                    @foreach($filters as $filter => $value)
                                        @if(!empty($value))
                                            <input type="hidden" name="{{ $filter }}" value="{{ $value }}">
                                        @endif
                                    @endforeach
                                    <label for="perPage">Items por página:</label>
                                    <select name="perPage" id="perPage" onchange="this.form.submit()">
                                        @foreach ($allowedPerPage as $perPageOption)
                                            <option value="{{ $perPageOption }}" {{ $perPage == $perPageOption ? 'selected' : '' }}>
                                                {{ $perPageOption }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Productos</h6>
                                <p class="display-6">{{ $products->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Almacenes</h6>
                                <p class="display-6">{{ $warehouses->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Movimientos</h6>
                                <p class="display-6">{{ $movements->total() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Stock Total</h6>
                                <p class="display-6">{{ $stockTotal->sum('stock') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5>Stock por Producto y Almacén</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockPerWarehouse as $s)
                                    <tr>
                                        <td>{{ $products->firstWhere('id', $s->product_id)->name ?? 'N/A' }}</td>
                                        <td>{{ $warehouses->firstWhere('id', $s->warehouse_id)->name ?? 'N/A' }}</td>
                                        <td class="text-center align-items-center text-white font-weight-bold 
                                            {{ $s->stock > 50 ? 'bg-success' : ($s->stock > 20 ? 'bg-warning' : 'bg-danger') }}">
                                             {{ $s->stock }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No hay datos de stock</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5>Stock Total por Producto</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $maxStock = $stockTotal->max('stock');
                        @endphp
                        <table class="table table-striped table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    @php
                                        $stock = $stockTotal->firstWhere('product_id', $product->id)->stock ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $product->name ?? 'N/A' }}</td>
                                        <td class="text-center align-items-center text-white font-weight-bold 
                                            {{ $stock > 50 ? 'bg-success' : ($stock > 20 ? 'bg-warning' : 'bg-danger') }}">
                                             {{ $stock }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">No hay datos de stock</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
