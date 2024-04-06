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
          'title' => $this->title,
          'message' => $this->message,
          'aim' => $this->aim,
          'code' => $this->code,
          'program_coordinator' => $program->users()->first()->name
        ];
        return $data;
    }
}
