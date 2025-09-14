@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Producto</h1>

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

                <div id="manualMovement" class="collapse" aria-labelledby="manualMovementHeading" data-bs-parent="#inventoryAccordion">
                    <div class="card-body">
                        <form action="{{ route('product.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" name="sku" id="sku" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">                                   
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                <div class="card-header" id="manualMovementHeading">
                    
                        Productos  /  <a href="{{ route('config.index') }}">Configuracion</a>
                    
                </div>
                <div class="row p-3">
                <div class="row"> 
                    <div class="col-1">  
                        
                    </div>                
                    <div class="col-5">
                        <b>Nombre</b>
                    </div>
                    <div class="col-2">
                        <b>SKU</b>
                    </div>
                    <div class="col-3">
                        <b>Created</b>
                    </div>   
                    <div class="col-1">  
                        
                    </div>                                   
                </div>
                @foreach ($producto as $prod)
                <div class="row">    
                     <div class="col-1 text-center">  
                        <a href="{{ route('product.update',['id'=>$prod->id]) }}">
                            <i class="bi bi-pencil-square"></i> 
                        </a>
                    </div>                
                    <div class="col-5">                       
                        <a href="{{ route('product.view',['id'=>$prod->id]) }}">{{ $prod->name }}</a>
                    </div>
                    <div class="col-2">
                        {{ $prod->sku }}
                    </div>
                     <div class="col-3">
                        @if ($prod->created_at)                       
                            {{ date('d M Y h:m A', strtotime($prod->created_at)) }}
                        @endif
                    </div>  
                    <div class="col-1">  
                        <a href="{{ route('product.storedelete',['id'=>$prod->id]) }}">
                            <i class="bi bi-trash3"></i>
                        </a>
                    </div>                 
                                               
                </div>            
                @endforeach  
            
                <div class="row p-3">
                    {{ $producto->links() }}
                </div>                

                </div>
            </div>
        
                </div>
            </div>
        </div>
    </div>
@endsection
