<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Order Management API",
 *     description="API documentation for the Order Management System",
 *     @OA\Contact(
 *         email="niyonshutivalentin@gmail.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local Server"
 * )
 */
class BaseApiController extends Controller {}
