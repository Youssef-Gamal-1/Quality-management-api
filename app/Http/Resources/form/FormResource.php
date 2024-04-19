<?php

namespace App\Http\Resources\form;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'value' => $this->value,
            'type' => $this->type,
            'status' => $this->status,
        ];
    }
}
