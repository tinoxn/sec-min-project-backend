<?php

namespace App\Repositories\Interfaces;

use App\Models\OrderItem;

interface OrderItemInterface
{
    public function create(array $data): OrderItem;
    public function update(OrderItem $orderItem, array $data): bool;
    public function delete(OrderItem $orderItem): bool;
    public function findById(int $id): ?OrderItem;
}