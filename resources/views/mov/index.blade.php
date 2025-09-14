@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Movimientos inventario</h1>

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
                            Movimientos
                            <i class="fas fa-chevron-down" id="manualMovementIcon"></i>
                        </button>
                    </h2>
                </div>
                <div class="row p-3">
                <div class="row">
                    <div class="col-2">
                        Fecha
                    </div>
                    <div class="col-4">
                        Producto
                    </div>
                    <div class="col-1">
                        Cantidad
                    </div>
                    <div class="col-3">
                        Warehouse
                    </div>
                </div>
                @foreach ($mov as $prod)
                <div class="row">
                    <div class="col-2">
                        {{ $prod->moved_at }}
                    </div>
                    <div class="col-4">                                            
                        <a href="{{ route('product.view',['id'=>$prod->product_id]) }}">{{ $prod->product->name }}</a>
                    </div>
                    <div class="col-1">
                        {{ $prod->quantity }}
                    </div>
                    <div class="col-3">
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
@endsection
