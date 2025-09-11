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
        <div>
            <h2 class="mt-5">Movimientos de inventario</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Almacén</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Referencia</th>
                        <th>Fecha - Movimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movs as $mov)
                        <tr>
                         <td>{{ $mov->product->name }}</td>
                          <td>{{ $mov->warehouse->name }}</td>
                            <td>{{ $mov->type }}</td>
                            <td>{{ $mov->quantity }}</td>
                            <td>{{ $mov->reference }}</td>
                            <td>{{ $mov->moved_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h2 class="mt-5">Stock Producto / Almac</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Almacén</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockTotal as $stock)
                    <tr>
                        <td>{{ $stock->product->name }}</td>
                        <td>{{ $stock->warehouse->name }}</td>
                        <td>{{ $stock->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
