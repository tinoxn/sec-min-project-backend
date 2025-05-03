<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     type="object",
 *     required={"id", "name", "price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Bluetooth Speaker"),
 *     @OA\Property(property="price", type="number", format="float", example=49.99),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-01T12:00:00Z")
 * )
 */
class ProductSchema {}