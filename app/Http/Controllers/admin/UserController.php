<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\admin\UserCollection;
use App\Http\Resources\admin\UserResource;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();

        return new UserCollection($users);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        unset($data['confirm-password']);
        $user = User::create($data);

        return response()->json([
            'msg' => 'User created successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        if(isset($data['confirm-password']))
        {
            unset($data['confirm-password']);
        }
        $user = User::findOrFail($user->id);
        $user->update($data);

        return response()->json([
            'msg' => 'User data updated successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'msg' => 'User deleted successfully!'
        ]);
    }
}
