<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\admin\UserCollection;
use App\Http\Resources\admin\UserResource;
use App\Http\Traits\AttachUserRelationships;
use App\Models\Program;
use App\Models\Standard;
use App\Models\User;
use Error;
use Illuminate\Http\JsonResponse;
use Mockery\Exception;


class UserController extends Controller
{
    use AttachUserRelationships;
    public function index()
    {
        $filters = request()->only([
            'search',
            'role'
        ]);
        $users = User::latest()->filter($filters)->paginate();

        return new UserCollection($users);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        unset($data['confirm-password']);

        $user = User::create($data);
        try {
            $this->attachRelationships($user, $data);
        } catch (\Exception $e) {
            $user->delete();
            throw new Exception($e->getMessage());
        }

        return response()->json([
            'success' => 'User created successfully!',
            'user' => new UserResource($user)
        ], 200);
    }
    public function show(User $user)
    {
        return new UserResource($user);
    }
    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        // Remove confirm-password field if present
        if(isset($data['confirm-password']))
        {
            unset($data['confirm-password']);
        }
        $user->update($data);
        if($user->TS === false)
        {
            $user->courses()->sync([]);
        }
        if($user->SC === false)
        {
            $standards = Standard::where('user_id',$user->id)->get();
            foreach($standards as $standard)
            {
                $standard->update(['user_id' => null]);
            }
        }
        $this->attachRelationships($user, $data);

        return response()->json([
            'success' => 'User data updated successfully!',
            'user' => new UserResource($user)
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => 'User deleted successfully!'
        ], 200);
    }

}
