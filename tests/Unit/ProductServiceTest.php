<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Mockery\MockInterface; // Add this instead


class ProductServiceTest extends TestCase
{
    private ProductService $productService;
    private ProductRepositoryInterface|MockInterface $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = \Mockery::mock(ProductRepositoryInterface::class);
        $this->productService = new ProductService($this->mockRepository);
    }

    public function test_it_can_return_all_products()
    {
        $mockProducts = new Collection([
            new Product(['name' => 'Product 1', 'price' => 100]),
            new Product(['name' => 'Product 2', 'price' => 200]),
            new Product(['name' => 'Product 3', 'price' => 200]),
        ]);

        $this->mockRepository->shouldReceive('all')
            ->once()
            ->andReturn($mockProducts);

        $products = $this->productService->getAll();

        $this->assertCount(3, $products);
        $this->assertInstanceOf(Collection::class, $products);
    }

    public function test_it_can_store_a_product()
    {
        $data = ['name' => 'Test', 'price' => 99.99];
        $mockProduct = new Product($data);

        $this->mockRepository->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($mockProduct);

        $product = $this->productService->store($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test', $product->name);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}