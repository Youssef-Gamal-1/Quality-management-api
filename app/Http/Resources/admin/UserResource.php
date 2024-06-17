<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = [];

        if ($this->QM === 1) {
            $role[] = 'Quality manager';
        }

        if ($this->SC === 1) {
            $role[] = 'Standard Coordinator';
        }

        if ($this->PC === 1) {
            $role[] = 'Program Coordinator';
        }

        if ($this->TS === 1) {
            $role[] = 'Teaching Staff';
        }

        if ($this->QU === 1) {
            $role[] = 'Questioner';
        }

        if ($this->EC === 1) {
            $role[] = 'Evaluator';
        }

        if ($this->ST === 1) {
            $role[] = 'Student';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'academic_email' => $this->academic_email,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'role' => empty($role) ? 'user' : implode(' | ', $role),
            'activated' => $this->activated
        ];
    }
}
