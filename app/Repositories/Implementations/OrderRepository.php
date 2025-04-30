<?php

namespace App\Repositories\Implementations;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(array $filters = [])
    {
        return Order::with('items')
            ->when($filters['customer_name'] ?? null, fn($q, $v) => $q->where('customer_name', 'like', "%$v%"))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['date_from'] ?? null, fn($q, $v) => $q->whereDate('order_date', '>=', $v))
            ->when($filters['date_to'] ?? null, fn($q, $v) => $q->whereDate('order_date', '<=', $v))
            ->get();
    }

    public function find(int $id)
    {
        return Order::with('items')->findOrFail($id);
    }

    public function create(array $data)
    {
        $order = Order::create($data);
        $order->items()->createMany($data['items']);
        $order->total_price = $order->items->sum('total');
        $order->save();

        return $order;
    }

    public function update(int $id, array $data)
    {
        $order = Order::findOrFail($id);
        $order->update($data);

        if (isset($data['items'])) {
            $order->items()->delete();
            $order->items()->createMany($data['items']);
        }

        $order->total_price = $order->items->sum('total');
        $order->save();

        return $order;
    }

    public function delete(int $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
    }
}
