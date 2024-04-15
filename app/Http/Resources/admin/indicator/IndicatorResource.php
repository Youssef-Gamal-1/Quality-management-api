<?php

namespace App\Http\Resources\admin\indicator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndicatorResource extends JsonResource
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
            'number' => $this->number,
            'number_of_forms' => $this->number_of_forms,
            'forms' => $this->forms()->get()
        ];
    }
}
