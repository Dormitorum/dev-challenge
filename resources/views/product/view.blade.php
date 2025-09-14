@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Producto </h1>

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
                        <h4><a href="{{ route('product.index') }}">Producto</a></h4>                        
                    </h2>
                </div>
                <div class="row p-5">
                    <div class="row">
                       <h3> {{ $product_->name }} </h2>
                    </div>
                    <div class="row">
                        <p>SKU : {{ $product_->sku }} </p>
                    </div>                   
                </div>           
                
                <div class="row p-5">   
                    <div class="row">  
                        <p>Stock x Warehouse</p>                    
                    </div>  
                     @foreach ($stockxwareh as $elem)
                     <div class="row">
                        <div class="col-3">
                            {{ $elem->warehouse->name }}
                        </div>                         
                        <div class="col-2">
                            {{ $elem->stock }}   {{ $product_->sku }}
                        </div>                        
                        <div class="col">
                           
                        </div> 
                    </div>
                    @endforeach 
                </div>
        </div>
        
    </div>
@endsection
