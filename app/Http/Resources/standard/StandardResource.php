<?php

namespace App\Http\Resources\standard;

use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->id,
            'Title' => $this->title,
            'Standard Coordinator' => $this->user->name ?? "Not associated yet!"
        ];
    }
}
