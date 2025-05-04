<?php

namespace App\Repositories\Implementations;

use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderItemInterface;

class OrderItemRepository implements OrderItemInterface
{
    public function create(array $data): OrderItem
    {
        return OrderItem::create($data);
    }

    public function update(OrderItem $orderItem, array $data): bool
    {
        return $orderItem->update($data);
    }

    public function delete(OrderItem $orderItem): bool
    {
        return $orderItem->delete();
    }

    public function findById(int $id): ?OrderItem
    {
        return OrderItem::find($id);
    }
}