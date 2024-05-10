<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\admin\UserCollection;
use App\Http\Resources\admin\UserResource;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    public function index()
    {
        $users = User::where('QM','!=',true)->get();
        return new UserCollection($users);
    }
    public function show(User $user)
    {
        return new UserResource($user);
    }
    public function update(UpdateRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        return new UserResource($user);
    }
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['success' => 'User deleted successfully!']);
    }

    public function authorizeUser(User $user)
    {
        $this->authorize('authorizeUser', $user);
        $user = User::findOrFail($user->id);

        $role = [];
        $standards = [];
        $programId = null;

        if ($user->QM === 1) {
            $role[] = 'Quality manager';
        }
        if ($user->SC === 1) {
            $role[] = 'Standard Coordinator';
            $standards[] = Standard::where('user_id',$user->id)->first()->id;
        } else {
            $standards = $user->permissions()->whereNotNull('standard_id')->pluck('standard_id');
        }
        if ($user->PC === 1) {
            $role[] = 'Program Coordinator';
            $programId = $user->programs()->first()->id;
        }
        if ($user->TS === 1) {
            $role[] = 'Teaching Staff';
            if(!isset($role['PC'])) {
                $programId = $user->programs()->first()->id;
            }
        }

        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $role,
            'standards' => $standards,
            'programId' => $programId,
        ];

        return response()->json($user);
    }
}
