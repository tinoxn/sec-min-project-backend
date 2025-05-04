<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Mockery;

class OrderServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_orders_calls_repository_with_filters()
    {
        $filters = ['status' => 'pending'];
        $mockRepo = Mockery::mock(OrderRepositoryInterface::class);
        $mockRepo->shouldReceive('all')
            ->once()
            ->with($filters)
            ->andReturn(collect());

        $service = new OrderService($mockRepo);
        $result = $service->getAll($filters);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    public function test_create_order_uses_repository_create()
    {
        $data = [
            'customer_name' => 'John',
            'status' => 'pending',
            'order_date' => '2025-05-04',
            'items' => []
        ];
        $order = new Order($data);

        $mockRepo = Mockery::mock(OrderRepositoryInterface::class);
        $mockRepo->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($order);

        $service = new OrderService($mockRepo);
        $result = $service->create($data);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals('John', $result->customer_name);
    }

    public function test_update_order_calls_repository_update()
    {
        $id = 1;
        $data = ['status' => 'shipped'];
        $updatedOrder = new Order(array_merge(['id' => $id], $data));

        $mockRepo = Mockery::mock(OrderRepositoryInterface::class);
        $mockRepo->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn($updatedOrder);

        $service = new OrderService($mockRepo);
        $result = $service->update($id, $data);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals('shipped', $result->status);
    }

    public function test_delete_order_calls_repository_delete()
    {
        $id = 1;
        $mockRepo = Mockery::mock(OrderRepositoryInterface::class);
        $mockRepo->shouldReceive('delete')
            ->once()
            ->with($id);

        $service = new OrderService($mockRepo);
        $service->delete($id);

        $this->assertTrue(true); // If no exception is thrown, test passes
    }
}