<?php

use Illuminate\Database\Seeder;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Warehouse;

class InventoryMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products   = Product::all();
        $warehouses = Warehouse::all();

        if ($products->isEmpty() || $warehouses->isEmpty()) {
            $this->command->warn('Seeder de movimientos: primero ejecuta ProductSeeder y WarehouseSeeder.');
            return;
        }

        $this->command->info('Generando 20 movimientos de inventario aleatorios...');


        for ($i = 0; $i < 20; $i++) {
            $productId   = $products->firstWhere('id', rand(1, 2))->id;
            $warehouseId = $warehouses->firstWhere('id', rand(1, 2))->id;;
            $type      = rand(0, 1) ? 'IN' : 'OUT';
            $quantity  = rand(1, 15);
            // $reference = "Ref-" + $type + "-" . rand(1, 15);
            $reference = $i ;

            // In case of OUT, check stock. If not enough stock, change to IN.
            $this->command->info('$productId->id:', $productId);
            if ($type === 'OUT') {
                $stock = InventoryMovement::currentStockByProductANdWherehouse($productId, $warehouseId) ?? 0;
                if ($stock < $quantity) {
                    $type = 'IN';
                }
            }

            InventoryMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => $type,
                'quantity' => $quantity,
                // 'reference' => $reference,
                'moved_at' => now(),
            ]);
        }

        $this->command->info('Movimientos generados correctamente.');
    }
}
