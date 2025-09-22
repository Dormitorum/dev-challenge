<?php

namespace App\Services;

use App\Models\InventoryMovement;
use InvalidArgumentException;
use Exception;

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

        if ($type === 'OUT') {
            $stock = InventoryMovement::currentStockByProductANdWherehouse($productId, $warehouseId);

            if ($stock < $quantity) {
                throw new Exception("Stock insuficiente para realizar la salida.");
            }
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
}
