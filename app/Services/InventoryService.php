<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Product;
use InvalidArgumentException;

class InventoryService
{
    public function registerMovement(
        int $productId,
        int $warehouseId,
        string $type, // IN|OUT
        int $quantity,
        ?string $reference
    ): InventoryMovement {
        if (!in_array($type, ['IN', 'OUT'])) {
            throw new InvalidArgumentException('Tipo de movimiento inválido');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('La cantidad debe ser mayor que 0');
        }

        return InventoryMovement::create([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'type' => $type,
            'quantity' => $quantity,
            'reference' => $reference,
            'moved_at' => now(),
        ]);
    }

    // CREATE Product
    //----------------------------
    public function createProduct(
        string $name,
        string $sku
    ): Product {
       
        return Product::create([
            'name' => $name,
            'sku' => $sku,            
            'create_at' => now(),
        ]);
    }

     // UPDATE Product
    //----------------------------
    public function updateProduct(
        string $id,
        string $name,
        string $sku
    ): Product {

        $prod = Product::find($id);
        $prod->update(
            [        
            'name' => $name,
            'sku' => $sku
            ]        
        );
        $prod->save();

        return $prod;
    }

     // DELETE Product
    //----------------------------
    public function deleteProduct(
        string $id
    ): Product {

        $prod = Product::find($id);
        $prod->delete();

        return new Product;
    }
}
