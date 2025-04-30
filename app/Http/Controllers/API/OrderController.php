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
     *     summary="List orders",
     *     @OA\Parameter(name="customer_name", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="date_from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="date_to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Success")
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
     *         @OA\JsonContent(
     *             required={"customer_name", "status", "order_date", "items"},
     *             @OA\Property(property="customer_name", type="string"),
     *             @OA\Property(property="status", type="string", enum={"pending", "shipped", "completed", "cancelled"}),
     *             @OA\Property(property="order_date", type="string", format="date"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product", type="string"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="total", type="number", format="float")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'status' => 'required|in:pending,shipped,completed,cancelled',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        return response()->json(
            $this->orderService->create($validated),
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Get an order by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not Found")
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="customer_name", type="string", example="John Doe"),
     *             @OA\Property(property="status", type="string", enum={"pending", "shipped", "completed", "cancelled"}, example="shipped"),
     *             @OA\Property(property="order_date", type="string", format="date", example="2025-04-30"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product", type="string", example="Laptop"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="price", type="number", format="float", example=1200.50),
     *                     @OA\Property(property="total", type="number", format="float", example=2401.00)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,shipped,completed,cancelled',
            'order_date' => 'sometimes|required|date',
            'items' => 'sometimes|required|array|min:1',
            'items.*.product' => 'required_with:items|string',
            'items.*.quantity' => 'required_with:items|integer',
            'items.*.price' => 'required_with:items|numeric',
            'items.*.total' => 'required_with:items|numeric',
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy($id)
    {
        $this->orderService->delete($id);
        return response()->json(null, 204);
    }
}
