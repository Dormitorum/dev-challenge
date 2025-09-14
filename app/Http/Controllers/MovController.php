<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryMovement;
use Illuminate\Contracts\Pagination\Paginator;

class MovController extends Controller
{
    // Index
    public function Index()
    {
        $mov_ = InventoryMovement::query()->simplePaginate(10);

        return view('mov.index',
                    ['mov'=>$mov_]
                    );
    }

    
}


