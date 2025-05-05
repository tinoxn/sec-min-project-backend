<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Order Management API"
 * )
 * 
 * @OA\Schema(
 *     schema="Order",
 *     required={"id", "order_number", "customer_name", "customer_code", "status", "order_date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_number", type="string", example="ORD-12345"),
 *     @OA\Property(property="customer_name", type="string", example="John Doe"),
 *     @OA\Property(property="customer_code", type="string", example="CUST001"),
 *     @OA\Property(
 *         property="status", 
 *         type="string",
 *         enum={"pending", "shipped", "completed", "cancelled"},
 *         example="pending"
 *     ),
 *     @OA\Property(property="order_date", type="string", format="date", example="2025-05-01"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrderItem")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="List all orders with optional filters",
     *     @OA\Parameter(
     *         name="customer_name",
     *         in="query",
     *         description="Filter by customer name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="customer_code",
     *         in="query",
     *         description="Filter by customer code",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_number",
     *         in="query",
     *         description="Filter by order number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string", enum={"pending", "shipped", "completed", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter orders from this date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter orders to this date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Order")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->orderService->getAll($request->all())
        );
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order data with items",
     *         @OA\JsonContent(
     *             required={"order_number", "customer_name", "customer_code", "status", "order_date", "items"},
     *             @OA\Property(property="order_number", type="string", example="ORD-12345", maxLength=20),
     *             @OA\Property(property="customer_name", type="string", example="John Doe"),
     *             @OA\Property(property="customer_code", type="string", example="CUST001", maxLength=20),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"pending", "shipped", "completed", "cancelled"},
     *                 example="pending"
     *             ),
     *             @OA\Property(
     *                 property="order_date",
     *                 type="string",
     *                 format="date",
     *                 example="2025-05-01"
     *             ),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 minItems=1,
     *                 @OA\Items(
     *                     required={"product_id", "quantity", "price", "total"},
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2, minimum=1),
     *                     @OA\Property(property="price", type="number", format="float", example=99.99, minimum=0),
     *                     @OA\Property(property="total", type="number", format="float", example=199.98, minimum=0)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:20',
            'customer_name' => 'required|string',
            'customer_code' => 'required|string|max:20',
            'status' => 'required|in:pending,shipped,completed,cancelled',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $order = $this->orderService->create($validated);

        if (isset($validated['items'])) {
            $order->items()->createMany($validated['items']);
        }

        $order->load('items');

        return response()->json($order, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Get order details by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show($id)
    {
        return response()->json($this->orderService->getById($id));
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Update an existing order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order data with items",
     *         @OA\JsonContent(
     *             @OA\Property(property="order_number", type="string", example="ORD-12345", maxLength=20),
     *             @OA\Property(property="customer_name", type="string", example="John Doe"),
     *             @OA\Property(property="customer_code", type="string", example="CUST001", maxLength=20),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"pending", "shipped", "completed", "cancelled"},
     *                 example="shipped"
     *             ),
     *             @OA\Property(
     *                 property="order_date",
     *                 type="string",
     *                 format="date",
     *                 example="2025-05-01"
     *             ),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Required for existing items", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2, minimum=1),
     *                     @OA\Property(property="price", type="number", format="float", example=99.99, minimum=0),
     *                     @OA\Property(property="total", type="number", format="float", example=199.98, minimum=0),
     *                     @OA\Property(property="_destroy", type="boolean", description="Set to true to delete this item")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_number' => 'sometimes|required|string|max:20',
            'customer_name' => 'sometimes|required|string',
            'customer_code' => 'sometimes|required|string|max:20',
            'status' => 'sometimes|required|in:pending,shipped,completed,cancelled',
            'order_date' => 'sometimes|required|date',
            'items' => 'sometimes|required|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
            'items.*.total' => 'required_with:items|numeric|min:0',
        ]);

        return response()->json(
            $this->orderService->update($id, $validated)
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Delete an order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->orderService->delete($id);
        return response()->json(null, 204);
    }
}
