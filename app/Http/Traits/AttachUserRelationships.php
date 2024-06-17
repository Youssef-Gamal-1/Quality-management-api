<?php

namespace App\Http\Traits;

use App\Models\Program;
use App\Models\Standard;

trait AttachUserRelationships
{
    public function attachRelationships($user, $data): void
    {
        $this->attachStandard($user, $data);
        $this->attachProgramCoordinator($user, $data);
        $this->attachTeachingStaffPrograms($user, $data);
    }

    public function attachStandard($user, $data): void
    {
        $requestStandard = $this->validateStandardCoordinator($data);
        if ($requestStandard) {
            $standards = Standard::where('title', $requestStandard->title)->get();
            $standards->each(function ($standard) use ($user) {
                $standard->user_id = $user->id;
                $standard->save();
            });
        }
    }
    public function attachProgramCoordinator($user, $data): void
    {
        $program = $this->validateProgramCoordinator($data);
        if ($program) {
            $user->programs()->sync($program->id);
        }
    }
    public function attachTeachingStaffPrograms($user, $data): void
    {
        $programs = $this->validateTeachingStaffPrograms($data);
        if (!empty($programs)) {
            $user->courses()->sync($programs);
        }
    }
    public function validateStandardCoordinator(array $data)
    {
        $requestStandard = null;
        if(isset($data['standard_id']))
        {
            $requestStandard = Standard::findOrFail($data['standard_id']);
            if($requestStandard->user()->exists())
            {
                return response()->json([
                    "error" => 'Standard already has a coordinator'
                ], 422);
            }
            unset($data['standard_id']);
        }
        return $requestStandard;
    }
    public function validateProgramCoordinator(array $data)
    {
        $program = null;
        if(isset($data['program_id']))
        {
            $program = Program::findOrFail($data['program_id']);
            if($program->users()->exists() && $program->users()->first()->PC === 1)
            {
                return response()->json([
                    "error" => 'Program already has a coordinator'
                ], 422); // Use 422 status code for validation errors
            }
            unset($data['program_id']);
        }
        return $program;
    }
    public function validateTeachingStaffPrograms(array $data): array
    {
        $programs = [];
        if(isset($data['programs']))
        {
            $programs = $data['programs'];
            unset($data['programs']);
        }
        return $programs;
    }
}
