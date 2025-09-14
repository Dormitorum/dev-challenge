@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Configuración</h1>

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
                            Configuración
                            <i class="fas fa-chevron-down" id="manualMovementIcon"></i>
                        </button>
                    </h2>
                </div>
                           
                <div class="row p-4">                   
                    <div class="row p-8">                           
                        <a href="{{ route('product.index')}}" class="text-left">Productos</p>                         
                    </div>
                    <div class="row p-8">                           
                        <p class="text-left">Warehouse</p>                          
                    </div>                                          
                </div>

            </div>
        </div>
    </di>
@endsection
