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
		
		<div style="height:16px;"></div>
		{{-- FILTROS DE LISTA DE MOVIMIENTOS --}}
		<form method="GET" class="mb-3">
		  <div class="row g-2">
			<div class="col-md-3">
			  <label class="form-label mb-1">Producto</label>
			  <select name="product_id" class="form-select">
				<option value="">Todos</option>
				@foreach($products as $p)
				  <option value="{{ $p->id }}" {{ ($filters['product_id'] ?? '') == $p->id ? 'selected' : '' }}>
					{{ $p->name ?? ('#'.$p->id) }}
				  </option>
				@endforeach
			  </select>
			</div>

			<div class="col-md-3">
			  <label class="form-label mb-1">Almacén</label>
			  <select name="warehouse_id" class="form-select">
				<option value="">Todos</option>
				@foreach($warehouses as $w)
				  <option value="{{ $w->id }}" {{ ($filters['warehouse_id'] ?? '') == $w->id ? 'selected' : '' }}>
					{{ $w->name ?? ('#'.$w->id) }}
				  </option>
				@endforeach
			  </select>
			</div>

			<div class="col-md-2">
			  <label class="form-label mb-1">Tipo</label>
			  <select name="type" class="form-select">
				<option value="">Todos</option>
				<option value="IN"  {{ ($filters['type'] ?? '') === 'IN'  ? 'selected' : '' }}>Entrada</option>
				<option value="OUT" {{ ($filters['type'] ?? '') === 'OUT' ? 'selected' : '' }}>Salida</option>
			  </select>
			</div>

			<div class="col-md-2">
			  <label class="form-label mb-1">Desde</label>
			  <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control">
			</div>

			<div class="col-md-2">
			  <label class="form-label mb-1">Hasta</label>
			  <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control">
			</div>
		  </div>

		  <div class="mt-2">
			<button class="btn btn-primary">Filtrar</button>
			<a href="{{ url()->current() }}" class="btn btn-outline-secondary">Limpiar</a>
		  </div>
		</form>

		{{-- LISTA DE MOVIMIENTOS DEL INVENTARIO  --}}
		<div class="card mb-4">
			<div class="card-header">Movimientos</div>
			<div class="card-body p-0">
				<table class="table table-striped mb-0">
					<thead>
					<tr>
						<th>Fecha</th>
						<th>Producto</th>
						<th>Almacén</th>
						<th>Cantidad</th>
						<th>Referencia</th>
						<th class="text-center">Tipo</th>
					</tr>
					</thead>
					<tbody>
					@forelse($movements as $m)
						<tr>
							<td>{{ \Illuminate\Support\Carbon::parse($m->moved_at)->format('Y-m-d H:i') }}</td>
							<td>{{ $m->product->name ?? ('#'.$m->product_id) }}</td>
							<td>{{ $m->warehouse->name ?? ('#'.$m->warehouse_id) }}</td>
							<td>{{ number_format($m->quantity) }}</td>
							<td>{{ $m->reference }}</td>
							<td class="text-center">
								<span class="{{ $m->type === 'IN' ? 'text-success' : 'text-danger' }}">
									{{ $m->type === 'IN' ? 'ENTRADA' : 'SALIDA' }}
								</span>
							</td>
						</tr>
					@empty
						<tr><td colspan="6" class="text-center py-4">Sin resultados</td></tr>
					@endforelse
					</tbody>
				</table>
			</div>
        </div>
		
		
		{{-- CALCULAR STOCK --}}
		<div class="card mb-4">
		  <div class="card-header">Calcular stock</div>
		  <div class="card-body">
			@php
			  $selWh   = request('stock_warehouse_id');     
			  $selProd = request('stock_product_id');       

			  if ($selProd) {
				  if ($selWh) {
					  $row = $stockByWarehouse
							  ->where('product_id', (int)$selProd)
							  ->where('warehouse_id', (int)$selWh)
							  ->first();
					  $selStock = $row->stock ?? 0;
				  } else {
					  $selStock = isset($stockTotals[$selProd]) ? (int)$stockTotals[$selProd]->stock : 0;
				  }
			  } else {
				  if ($selWh) {
					  $selStock = (int) $stockByWarehouse->where('warehouse_id', (int)$selWh)->sum('stock');
				  } else {
					  $selStock = (int) $stockTotals->sum('stock');
				  }
			  }
			@endphp

			<form method="GET" action="{{ url()->current() }}">
			  <div class="row g-3 align-items-end">
			  
				<div class="col-md-4">
				  <label for="stock_warehouse_id" class="form-label">Almacén</label>
				  <select name="stock_warehouse_id" id="stock_warehouse_id" class="form-select" onchange="this.form.submit()">
					<option value="">Todos</option>
					@foreach ($warehouses as $w)
					  <option value="{{ $w->id }}" {{ (string)$selWh === (string)$w->id ? 'selected' : '' }}>
						{{ $w->name }}
					  </option>
					@endforeach
				  </select>
				</div>

				<div class="col-md-4">
				  <label for="stock_product_id" class="form-label">Producto</label>
				  <select name="stock_product_id" id="stock_product_id" class="form-select" onchange="this.form.submit()">
					<option value="">Todos</option>
					@foreach ($products as $p)
					  <option value="{{ $p->id }}" {{ (string)$selProd === (string)$p->id ? 'selected' : '' }}>
						{{ $p->name }}
					  </option>
					@endforeach
				  </select>
				</div>

				<div class="col-md-4">
				  <label class="form-label">Stock</label>
				  <input type="text" class="form-control text-center fw-bold" value="{{ $selStock }}" readonly>
				</div>
			  </div>
			</form>
		  </div>
  
		</div>
		{{-- LISTA DE STOCK --}}
		<div class="card mb-4">
		  <div class="card-header">Stock por producto y almacén</div>
		  <div class="card-body p-0">
			<table class="table table-striped mb-0">
			  <thead>
				<tr>
				  <th>Producto</th>
				  <th>Almacén</th>
				  <th>Stock</th>
				</tr>
			  </thead>
			  <tbody>
				@foreach ($stockByWarehouse as $row)
				  <tr>
					<td>{{ $products->firstWhere('id', $row->product_id)->name ?? '#' }}</td>
					<td>{{ $warehouses->firstWhere('id', $row->warehouse_id)->name ?? '#' }}</td>
					<td>{{ $row->stock }}</td>
				  </tr>
				@endforeach
			  </tbody>
			</table>
		  </div>
		</div>

		<div>
			{{ $movements->links() }}
		</div>
    </div>
@endsection
