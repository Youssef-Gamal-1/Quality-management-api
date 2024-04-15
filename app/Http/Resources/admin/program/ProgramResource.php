<?php

namespace App\Http\Resources\admin\program;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $program = Program::find($this->id);
        $data = [
              'id' => $this->id,
              'title' => $this->title,
              'message' => $this->message,
              'aim' => $this->aim,
              'code' => $this->code,
              'goals' => $this->goals,
              'program_coordinator' => $program->users()->first()->name ?? 'Not associated yet!'
        ];
        return $data;
    }
}
