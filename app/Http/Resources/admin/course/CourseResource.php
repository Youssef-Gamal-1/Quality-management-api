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
        $teachers = $this->users()->pluck('name')->implode(', ') ?? "Not associated yet!";

        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'hours' => $this->hours,
            'doctor' => $teachers,
            'programs' => $this->programs
        ];
    }
}
