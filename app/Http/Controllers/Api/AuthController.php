<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Endpoints for user authentication"
 * )
 */
class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login and generate token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Token generated successfully"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email )->first();
        if (! $user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('User or password invalid');
        }
        $user->tokens()->delete();
        $token = $user->createToken('my-app-token', ['*'], now()->addHours(1))->plainTextToken;
        return $this->successResponse([
            'token' => $token,
        ]);

    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout and revoke token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Logged out successfully")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse([
            'status' => 'Successfully logged out',
        ]);
    }
}
