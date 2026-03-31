<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
{
    /**
     * Get all inventory records
     */
    public function getAll()
    {
        return Inventory::with('product')->get();
    }

    /**
     * Get inventory by product ID
     */
    public function getByProductId(int $productId): ?Inventory
    {
        return Inventory::where('productid', $productId)->first();
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(int $productId): bool
    {
        $inventory = $this->getByProductId($productId);
        return $inventory && $inventory->QuantityInStock > 0;
    }

    /**
     * Check product quantity
     */
    public function getQuantity(int $productId): int
    {
        $inventory = $this->getByProductId($productId);
        return $inventory ? $inventory->QuantityInStock : 0;
    }

    /**
     * Update quantity
     */
    public function updateQuantity(int $productId, int $quantity): Inventory
    {
        $inventory = Inventory::where('productid', $productId)->firstOrFail();
        $inventory->update(['QuantityInStock' => $quantity]);
        return $inventory;
    }

    /**
     * Decrease quantity (when selling)
     */
    public function decreaseQuantity(int $productId, int $amount): bool
    {
        $inventory = $this->getByProductId($productId);
        
        if (!$inventory || $inventory->QuantityInStock < $amount) {
            return false; // Not enough stock
        }

        $inventory->update([
            'QuantityInStock' => $inventory->QuantityInStock - $amount
        ]);
        return true;
    }

    /**
     * Increase quantity (restocking)
     */
    public function increaseQuantity(int $productId, int $amount): Inventory
    {
        $inventory = Inventory::where('productid', $productId)->firstOrFail();
        $inventory->update([
            'QuantityInStock' => $inventory->QuantityInStock + $amount,
            'LastRestocking' => now()
        ]);
        return $inventory;
    }

    /**
     * Create inventory for product
     */
    public function create(array $data): Inventory
    {
        return Inventory::create($data);
    }

    /**
     * Update inventory by product ID
     */
    public function update(int $productId, array $data): Inventory
    {
        $inventory = Inventory::where('productid', $productId)->firstOrFail();
        $inventory->update($data);
        return $inventory->fresh();
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts(int $threshold = 10)
    {
        return Inventory::where('QuantityInStock', '<=', $threshold)
            ->where('QuantityInStock', '>', 0)
            ->with('product')
            ->get();
    }

    /**
     * Get out of stock products
     */
    public function getOutOfStockProducts()
    {
        return Inventory::where('QuantityInStock', 0)
            ->with('product')
            ->get();
    }
}
