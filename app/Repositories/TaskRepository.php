<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TaskRepository
{
    protected $model;

    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function getAll(int $per_page = 10): array|LengthAwarePaginator|null
    {
        $tasks = $this->model->where('user_id', auth()->id())->paginate($per_page);

        return [
            'items' => $tasks->items(),
            'meta' => [
                'page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ]
        ];
    }

    public function filterByStatus($request): array|LengthAwarePaginator|null
    {

        $query = $this->model->query()->where('user_id', auth()->id());

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $tasks = $query->paginate($request->has('per_page') ? $request->per_page : 10);

        return [
            'items' => $tasks->items(),
            'meta' => [
                'page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ]
        ];
    }

    public function findById(int $id): ?Task
    {
        return $this->model->find($id);
    }


    public function findByColumn(string $column, $value): ?Task
    {
        return $this->model->where($column, $value)->first();
    }

    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }
}
