<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Modules\API\V1\Users\Repositories\UserRepositories;

class AuthService
{
    protected UserRepositories $userRepositories;

    public function __construct(UserRepositories $userRepositories)
    {
        $this->userRepositories = $userRepositories;
    }


    public function register(array $request): mixed
    {
        $user = $this->userRepositories->save($request);

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function login(array $request): mixed
    {
        $user = $this->userRepositories->getByEmail($request['email'])->first();

        if (!$user || !Hash::check($request['password'], $user['password'])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }
}
