<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     type="object",
 *     title="Order Item",
 *     required={"order_id", "product", "price", "quantity", "total"},
 * 
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_id", type="integer", example=10, description="ID of the related order"),
 *     @OA\Property(property="product", type="string", example="Wireless Headphones", description="Product name or description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="total", type="number", format="float", example=199.98),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-04T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-04T12:10:00Z")
 * )
 */
class OrderItemSchema {}