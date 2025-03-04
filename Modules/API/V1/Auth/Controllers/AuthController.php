<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Auth\Controllers;

use App\Http\Controllers\Controller;
use Modules\API\V1\Auth\Requests\LoginRequest;
use Modules\API\V1\Auth\Requests\RegisterRequest;
use Modules\API\V1\Auth\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['token' => $this->authService->register($request->toArray())], 201);
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['token' => $this->authService->login($request->toArray())]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logged out']);
    }
}
