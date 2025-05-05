<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     required={"id", "order_id", "product_id", "quantity", "price", "total"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_id", type="integer", example=1),
 *     @OA\Property(
 *         property="product_id",
 *         type="integer",
 *         description="Foreign key referencing products table",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         minimum=1,
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         minimum=0,
 *         example=99.99
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="number",
 *         format="float",
 *         minimum=0,
 *         example=199.98
 *     ),
 *     @OA\Property(
 *         property="product",
 *         type="object",
 *         description="The product details (auto-loaded relation)",
 *         ref="#/components/schemas/Product"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     )
 * )
 */

class OrderItemSchema {}
