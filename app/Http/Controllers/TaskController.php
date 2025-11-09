<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\EditTaskRequest;
use App\Http\Requests\Task\FilterTaskByStatusRequest;
use App\Http\Resources\Task\TaskResource;
use App\Services\TaskService;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use RespondsWithHttpStatus;

    public function __construct(protected TaskService $service) {}

    public function createTask(CreateTaskRequest $request): JsonResponse
    {

        $task = $this->service->createTask($request->validated());

        return $this->success('Task created successfully', data: new TaskResource($task));
    }

    /**
     * @throws CustomException
     */
    public function editTask(EditTaskRequest $request): JsonResponse
    {
        $task = $this->service->updateTask($request->validated());

        return $this->success('Task updated successfully', data: new TaskResource($task));
    }

    public function getTasks(Request $request): JsonResponse
    {
        $tasks = $this->service->getTasks($request->per_page);
        return $this->success(message: 'Tasks retrieved sucessfully', data: TaskResource::collection($tasks['items']), meta: $tasks['meta']);
    }

    public function filterTaskByStatus(FilterTaskByStatusRequest $request): JsonResponse
    {
        $tasks = $this->service->filterTaskByStatus($request);
        return $this->success(message: 'Tasks retrieved sucessfully', data: TaskResource::collection($tasks['items']), meta: $tasks['meta']);
    }

    public function viewTask(int $taskId): JsonResponse
    {
        $task = $this->service->viewTask($taskId);

        return $this->success(data: new TaskResource($task));
    }

    public function deleteTask(int $taskId): JsonResponse
    {
        $task = $this->service->deleteTask($taskId);

        return $this->success(message: 'Task deleted', data: new TaskResource($task));
    }
}
