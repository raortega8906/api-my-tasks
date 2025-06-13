<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints para autenticación de usuarios"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Authentication"},
     *     summary="Registrar nuevo usuario",
     *     description="Crea una nueva cuenta de usuario en el sistema",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="juan@ejemplo.com"),
     *             @OA\Property(property="password", type="string", minLength=5, example="secreto123"),
     *             @OA\Property(property="password_confirmation", type="string", minLength=5, example="secreto123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to register user"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        try {

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:5|confirmed',
            ]);

            $user = User::create($data);

            // $token = $user->createToken('auth-token')->plainTextToken;

            $data = [
                'message' => 'User registered successfully',
                'status' => 200,
                'data' => $user,
            ];

            return response()->json($data, 200);

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to register user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Authentication"},
     *     summary="Iniciar sesión",
     *     description="Autentica un usuario y retorna un token JWT",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="juan@ejemplo.com"),
     *             @OA\Property(property="password", type="string", minLength=5, example="secreto123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario autenticado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to login user"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        try {

            $data = $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:5',
            ]);

            $token = auth()->attempt($data);

            if (!$token) {
                
                return response()->json([
                    'message' => 'Invalid credentials',
                    'status' => 401,
                ], 401);

            } 
            else{

                return response()->json([
                    'message' => 'User logged in successfully',
                    'status' => 200,
                    'data' => $data,
                    'token' => $token
                ], 200);

            }

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to login user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
            
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Authentication"},
     *     summary="Cerrar sesión",
     *     description="Cierra la sesión del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged out successfully"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to logout user"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            
            auth()->logout();

            return response()->json([
                'message' => 'User logged out successfully',
                'status' => 200,
            ], 200);

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to logout user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/refresh",
     *     tags={"Authentication"},
     *     summary="Refrescar token JWT",
     *     description="Genera un nuevo token JWT para el usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refrescado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token refreshed successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="new_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to refresh token"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function refreshToken(): JsonResponse
    {
        try {

            $token = auth()->refresh();

            return response()->json([
                'message' => 'Token refreshed successfully',
                'status' => 200,
                'new_token' => $token
            ], 200);

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to refresh token',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Authentication"},
     *     summary="Obtener perfil del usuario",
     *     description="Retorna la información del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User retrieved successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to get user"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function profile(): JsonResponse
    {
        try {            

            $user = auth()->user();

            return response()->json([
                'message' => 'User retrieved successfully',
                'status' => 200,
                'data' => $user
            ], 200);

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to get user',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
            
        }
    }
}