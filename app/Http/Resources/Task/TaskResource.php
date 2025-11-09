<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        Arr::set($data, 'title',  $this['title']);
        Arr::set($data, 'description', $this['description'] != null);
        Arr::set($data, 'created_at', date('Y-m-d H:i:s', strtotime($this['created_at'])));
        Arr::set($data, 'owner', $this->user()->first(['id', 'name',  'email']));

        unset(
            $data['updated_at'],
        );

        return $data;
    }
}
