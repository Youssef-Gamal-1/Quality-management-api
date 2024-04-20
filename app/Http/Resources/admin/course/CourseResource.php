<?php

namespace App\Http\Resources\admin\course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'hours' => $this->hours,
            'user' => $this->users()->first()->name ?? "Not associated yet!"
        ];
    }
}
