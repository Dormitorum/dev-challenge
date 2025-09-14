@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Update Producto</h1>

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
                            Producto manual
                            <i class="fas fa-chevron-down" id="manualMovementIcon"></i>
                        </button>
                    </h2>
                </div>

                <div id="manualMovement"  aria-labelledby="manualMovementHeading" data-bs-parent="#inventoryAccordion">
                    <div class="card-body">
                        <form action="{{ route('product.storeupdate') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" name="name" id="name" value="{{ $producto->name }}" class="form-control">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" name="sku" id="sku" value="{{ $producto->sku }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">                                   
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{ $producto->id }}"> 
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
                
        
                </div>
            </div>
        </div>
    </div>
@endsection
