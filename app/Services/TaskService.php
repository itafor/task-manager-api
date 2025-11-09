<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Traits\RespondsWithHttpStatus;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class TaskService
{
    use RespondsWithHttpStatus;

    /** 
     * TaskService constructor.
     */
    public function __construct(
        protected TaskRepository $taskRepository,
    ) {}

    /**
     * Create a new task.
     * @throws Exception
     */
    public function createTask(array $data): Task
    {
        try {
            DB::beginTransaction();
            $data['user_id'] = auth()->id();
            $task = $this->taskRepository->create($data);

            DB::commit();
        } catch (Throwable $th) {
            $error = $th->getMessage();
            logger('Error creating task ' . $error);
            DB::rollBack();
            throw new Exception("Something went wrong: $error");
        }

        return $task;
    }

    /**
     * Update an existing task.
     * @throws CustomException
     */
    public function updateTask(array $data): Task|null
    {
        try {
            $task = $this->taskRepository->findById($data['task_id']);

            if (!$task) {
                throw new Exception('Task not found');
            }
            DB::beginTransaction();

            $data['title'] = $data['title'] ?? $task->title;
            $data['description'] = $data['description'] ?? $task->description;
            $data['status'] = $data['status'] ?? $task->status;
            unset($data['task_id']);
            $this->taskRepository->update($task?->id, $data);

            DB::commit();

            return  $task->fresh();
        } catch (\Throwable $th) {
            DB::rollBack();
            $error = $th->getMessage();
            throw new Exception("Something went wrong: $error");
        }
    }


    /**
     * Display all tasks.
     * @throws CustomException
     */
    public function getTasks($per_page = 15): LengthAwarePaginator|array|null
    {
        try {

            $tasks = $this->taskRepository->getAll($per_page);

            if (!$tasks) {
                return $this->notFound(message: 'Tasks not found', data: $tasks);
            }

            return $tasks;
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            throw new Exception("Something went wrong: $error");
        }
    }

    public function filterTaskByStatus($request): LengthAwarePaginator|array|null
    {
        try {

            $tasks = $this->taskRepository->filterByStatus($request);

            if (!$tasks) {
                return $this->notFound(message: 'Tasks not found', data: $tasks);
            }

            return $tasks;
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            throw new Exception("Something went wrong: $error");
        }
    }

    /**
     * View a specific task.
     * @throws CustomException
     */
    public function viewTask(int $taskId): Task|null
    {
        try {
            $task = $this->taskRepository->findById($taskId);

            if (!$task) {
                return $this->notFound(message: 'Task not found', data: $task);
            }

            return $task;
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            throw new Exception("Something went wrong: $error");
        }
    }


    /**
     * Delete an existing task.
     * @throws CustomException
     */
    public function deleteTask(int $taskId): Task|null
    {
        try {
            $task = $this->taskRepository->findById($taskId);

            if (!$task) {
                return $this->notFound(message: 'Task not found', data: $task);
            }

            $this->taskRepository->delete($task?->id);

            return $task;
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            throw new Exception("Something went wrong: $error");
        }
    }
}
