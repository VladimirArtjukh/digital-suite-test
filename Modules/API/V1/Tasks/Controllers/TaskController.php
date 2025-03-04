<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Tasks\Controllers;

use App\Http\Controllers\Controller;
use Modules\API\V1\Tasks\Enums\TasksEnum;
use Modules\API\V1\Tasks\Models\Tasks;
use Modules\API\V1\Tasks\Requests\CreateTasksRequest;
use Modules\API\V1\Tasks\Requests\FilterTasksRequest;
use Modules\API\V1\Tasks\Requests\UpdateTasksRequest;
use Modules\API\V1\Tasks\Resources\TasksResource;
use Modules\API\V1\Tasks\Services\TasksService;

class TaskController extends Controller
{
    protected TasksService $service;

    public function __construct(TasksService $service)
    {
        $this->service = $service;
    }

    public function index(FilterTasksRequest $request): TasksResource
    {
        return new TasksResource($this->service->index($request->all()));
    }

    public function show(Tasks $task): TasksResource|string
    {
        $this->authorizeTask($task);
        try {
            return new TasksResource($task);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . "\n";
        }
    }

    public function store(CreateTasksRequest $request): TasksResource|string
    {
        try {
            return new TasksResource($this->service->save($request->all()));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . "\n";
        }
    }

    public function update(UpdateTasksRequest $request, Tasks $task): TasksResource|string
    {
        $this->authorizeTask($task);
        try {
            $this->service->update($request->all(), $task);
            return new TasksResource(['message' => TasksEnum::SUCCESSFULL_UPDATE_MESSAGE->value]);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . "\n";
        }
    }

    public function destroy(Tasks $task): TasksResource|string
    {
        $this->authorizeTask($task);
        try {
            $task->delete();
            return new TasksResource(['message' => TasksEnum::SUCCESSFULL_DELETE_MESSAGE->value]);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . "\n";
        }
    }

    private function authorizeTask(Tasks $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
