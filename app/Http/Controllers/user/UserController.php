<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\admin\UserCollection;
use App\Http\Resources\admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $this->authorize('viewAny',auth()->user());
        $users = User::where('QM','!=',true)->get();
        return new UserCollection($users);
    }
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }
    public function update(UpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $validated = $request->validated();

        $user->update($validated);

        return new UserResource($user);
    }
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return response()->json(['success' => 'User deleted successfully!']);
    }
}
