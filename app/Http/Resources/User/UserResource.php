<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        Arr::set($data, 'name',  $this['name']);
        Arr::set($data, 'email', $this['email'] != null);
        Arr::set($data, 'created_at', date('Y-m-d H:i:s', strtotime($this['created_at'])));

        unset(
            $data['updated_at'],
            $data['email_verified_at'],
        );

        return $data;
    }
}
