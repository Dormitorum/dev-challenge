@php
    $columns = [
        'moved_at' => 'Fecha',
        'warehouse_id' => 'Almacén',
        'product_id' => 'Producto',
        'type' => 'Tipo',
        'quantity' => 'Cantidad',
        'reference' => 'Referencia'
    ];
@endphp

@foreach ($columns as $column => $label)
    @php
        $isCurrent = ($sortBy == $column);
        $nextDir = $isCurrent && $sortDir === 'asc' ? 'desc' : 'asc';
        $icon = $isCurrent ? ($sortDir === 'asc' ? '↑' : '↓') : '';
    @endphp
    <th scope="col">
        <a href="{{ route('inventory.index', array_merge(request()->except('page'), 
            ['sortBy' => $column,'sortDir' => $nextDir,])) }}" 
            class="text-decoration-none text-body">
            {{ $label }}
            @if ($icon)
                <span><small>{{ $icon }}</small></span>
            @endif
        </a>
    </th>
@endforeach
