<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderService
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAll(array $filters = [])
    {
        return $this->orderRepository->all($filters);
    }

    public function getById(int $id)
    {
        return $this->orderRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->orderRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->orderRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->orderRepository->delete($id);
    }
}
