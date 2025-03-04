<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Tasks\Services;

use Modules\API\V1\Tasks\Enums\TasksEnum;
use Modules\API\V1\Tasks\Models\Tasks;
use Modules\API\V1\Tasks\Repositories\TasksRepositories;
use Illuminate\Pagination\LengthAwarePaginator;

class TasksService
{
    protected TasksRepositories $repository;

    public function __construct(TasksRepositories $repositories)
    {
        $this->repository = $repositories;
    }

    public function index(array $request): LengthAwarePaginator
    {
        $tasks = $this->repository->get($request, auth()->id())->paginate((int)TasksEnum::PAGINATE->value);
        return $tasks;
    }

    public function save(array $request): Tasks
    {
        $request['user_id'] = auth()->id();
        return $this->repository->save($request);
    }


    public function update(array $request, $task): bool
    {
        return $this->repository->update($request, $task);
    }
}
