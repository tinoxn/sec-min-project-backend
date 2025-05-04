<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderItemInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderItemService
{
    protected OrderItemInterface $orderItemRepository;

    public function __construct(OrderItemInterface $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function create(array $data): OrderItem
    {
        try {
            return $this->orderItemRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating order item: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(OrderItem $orderItem, array $data): bool
    {
        try {
            return $this->orderItemRepository->update($orderItem, $data);
        } catch (Exception $e) {
            Log::error('Error updating order item: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(OrderItem $orderItem): bool
    {
        try {
            return $this->orderItemRepository->delete($orderItem);
        } catch (Exception $e) {
            Log::error('Error deleting order item: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id): ?OrderItem
    {
        try {
            return $this->orderItemRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error finding order item: ' . $e->getMessage());
            throw $e;
        }
    }
}