<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Tasks\Repositories;

use Modules\API\V1\Tasks\Models\Tasks;
use Illuminate\Database\Eloquent\Builder;

class TasksRepositories
{
    protected Tasks $model;

    public function __construct(Tasks $task)
    {
        $this->model = $task;
    }

    public function get(array $request, int $userId): Builder|Tasks
    {
        $model = $this->model->where('user_id', $userId);
        if (
            isset($request["search_field"]) &&
            isset($request["search_value"]) &&
            isset($request["search_field"]) != "" &&
            isset($request["search_value"]) != ""
        ) {
            $model = $model->where($request["search_field"], 'LIKE', '%' . $request["search_value"] . '%');
        }

        if (
            isset($request["sort_field"]) &&
            isset($request["sort_direction"]) &&
            isset($request["sort_field"]) != "" &&
            isset($request["sort_direction"]) != ""
        ) {
            $model = $model->orderBy($request["sort_field"], $request["sort_direction"]);
        }
        return $model;
    }

    public function save(array $request): Tasks
    {
        return $this->model->create($request);
    }

    public function update(array $request, $task): bool
    {
        return $task->update($request);
    }
}
