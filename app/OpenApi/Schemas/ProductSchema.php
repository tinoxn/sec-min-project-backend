<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Product",
 *     required={"id", "name", "price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Laptop"),
 *     @OA\Property(property="description", type="string", example="High-performance laptop"),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         example=999.99
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ProductSchema {}
