<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\OrderItemService;
use App\Repositories\Interfaces\OrderItemInterface;
use App\Models\OrderItem;
use App\Models\Product;
use App\Repositories\Implementations\OrderItemRepository;
use Mockery;

class OrderItemServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_order_item_calls_repository()
    {
        $mockRepo = $this->createMock(OrderItemRepository::class);
        $product = new Product(['name' => 'Widget']);
        $orderItem = new OrderItem();
        $orderItem->setRelation('product', $product);

        $data = ['product_id' => 1, 'quantity' => 2];

        $mockRepo->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($orderItem);

        $service = new OrderItemService($mockRepo);
        $result = $service->create($data);

        $this->assertInstanceOf(OrderItem::class, $result);
        $this->assertEquals('Widget', $result->product->name); // Check product name via relationship
    }

    public function test_update_order_item_calls_repository()
    {
        $existing = new OrderItem([
            'order_id'    => 1,
            'product'     => 'Widget',
            'quantity'    => 3,
            'price'       => 9.99,
            'total'       => 29.97,
        ]);
        $data = ['quantity' => 5, 'total' => 49.95];

        $mockRepo = Mockery::mock(OrderItemInterface::class);
        $mockRepo->shouldReceive('update')
            ->once()
            ->with($existing, $data)
            ->andReturn(true);

        $service = new OrderItemService($mockRepo);
        $result = $service->update($existing, $data);

        $this->assertTrue($result);
    }

    public function test_delete_order_item_calls_repository()
    {
        $existing = new OrderItem(['order_id' => 1, 'product' => 'X', 'quantity' => 1, 'price' => 1, 'total' => 1]);

        $mockRepo = Mockery::mock(OrderItemInterface::class);
        $mockRepo->shouldReceive('delete')
            ->once()
            ->with($existing)
            ->andReturn(true);

        $service = new OrderItemService($mockRepo);
        $result = $service->delete($existing);

        $this->assertTrue($result);
    }

    public function test_find_by_id_calls_repository()
    {
        $mockRepo = Mockery::mock(OrderItemInterface::class);
        $mockItem = new OrderItem(['order_id' => 1, 'product' => 'X', 'quantity' => 1, 'price' => 1, 'total' => 1]);

        $mockRepo->shouldReceive('findById')
            ->once()
            ->with(123)
            ->andReturn($mockItem);

        $service = new OrderItemService($mockRepo);
        $result = $service->findById(123);

        $this->assertInstanceOf(OrderItem::class, $result);
    }
}
