<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="OrderItemResource",
 *     type="object",
 *     title="Order Item Resource",
 *     description="Order Item Resource Response",
 *     @OA\Property(
 *         property="data",
 *         ref="#/components/schemas/OrderItem"
 *     )
 * )
 */
class OrderItemResourceSchema {}