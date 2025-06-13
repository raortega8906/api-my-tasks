<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Task Management API",
 *     version="1.0.0",
 *     description="API para gestión de tareas y categorías",
 *     @OA\Contact(
 *         email="contacto@taskapi.com",
 *         name="Soporte API"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api/v1",
 *     description="Servidor API V1"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Utiliza un token JWT en el header Authorization: Bearer {token}"
 * )
 * 
 * @OA\PathItem(
 *     path="/api/v1"
 * )
 */

class Controller
{
    //
}
