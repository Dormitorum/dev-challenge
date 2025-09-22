<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryMovement extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'warehouse_id', 'type', 'quantity', 'reference', 'moved_at'];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public static function stockPerWarehouse()
    {
        return DB::table('inventory_movements as im')
            ->select(
                'im.product_id',
                'im.warehouse_id',
                DB::raw("
                    SUM(CASE WHEN im.type = 'IN' THEN im.quantity ELSE 0 END)
                    - SUM(CASE WHEN im.type = 'OUT' THEN im.quantity ELSE 0 END) AS stock
                ")
            )
            ->groupBy('im.product_id', 'im.warehouse_id')
            ->get();
    }

    public static function stockTotalPerProducts()
    {
        return DB::table('inventory_movements as im')
            ->select(
                'im.product_id',
                DB::raw("
                    SUM(CASE WHEN im.type = 'IN' THEN im.quantity ELSE 0 END)
                    - SUM(CASE WHEN im.type = 'OUT' THEN im.quantity ELSE 0 END) AS stock
                ")
            )
            ->groupBy('im.product_id')
            ->get();
    }

    public static function currentStockByProductANdWherehouse($productId, $warehouseId)
    {
        return self::query()
            ->selectRaw("
                SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END)
                - SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END) as stock
            ")
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->value('stock') ?? 0;
    }

    public function scopeApplyFilters($query, $filters = [])
    {
        if (!is_array($filters) || empty($filters)) {
            return $query;
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['from']) && !empty($filters['to'])) {
            $query->whereBetween('created_at', [$filters['from'], $filters['to']]);
        }

        return $query;
    }
}
