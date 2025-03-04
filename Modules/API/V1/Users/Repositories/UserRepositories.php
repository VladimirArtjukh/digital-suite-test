<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Users\Repositories;

use Illuminate\Support\Facades\Hash;
use Modules\API\V1\Users\Models\User;

class UserRepositories
{
    protected User $model;

    public function __construct(User $task)
    {
        $this->model = $task;
    }

    public function save(array $request): mixed
    {
        return $this->model->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function getByEmail(string $email): mixed
    {
        return $this->model->where('email', $email);
    }
}
