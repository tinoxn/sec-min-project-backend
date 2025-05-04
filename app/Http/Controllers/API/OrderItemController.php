<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Models\OrderItem;
use App\Services\OrderItemService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Orders Items",
 *     description="Order Management API"
 * )
 */
class OrderItemController extends Controller
{
    protected OrderItemService $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    /**
     * @OA\Post(
     *     path="/api/order-items",
     *     summary="Create an order item",
     *     tags={"Order Items"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderItem")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order item created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderItemResource")
     *     )
     * )
     */
    public function store(OrderItemRequest $request): JsonResponse
    {
        $item = $this->orderItemService->create($request->validated());

        return response()->json(new OrderItemResource($item), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/order-items/{id}",
     *     summary="Get a specific order item",
     *     tags={"Order Items"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order item",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/OrderItemResource")
     *     )
     * )
     */
    public function show(OrderItem $orderItem): JsonResponse
    {
        return response()->json(new OrderItemResource($orderItem));
    }

    /**
     * @OA\Put(
     *     path="/api/order-items/{id}",
     *     summary="Update an order item",
     *     tags={"Order Items"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order item",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderItem")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order item updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderItemResource")
     *     )
     * )
     */
    public function update(OrderItemRequest $request, OrderItem $orderItem): JsonResponse
    {
        $this->orderItemService->update($orderItem, $request->validated());

        return response()->json(new OrderItemResource($orderItem));
    }

    /**
     * @OA\Delete(
     *     path="/api/order-items/{id}",
     *     summary="Delete an order item",
     *     tags={"Order Items"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order item",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Deleted successfully"
     *     )
     * )
     */
    public function destroy(OrderItem $orderItem): JsonResponse
    {
        $this->orderItemService->delete($orderItem);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}