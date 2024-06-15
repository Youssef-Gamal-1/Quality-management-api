<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Permission;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPermissionsController extends Controller
{
    public function index(User $user): \Illuminate\Http\JsonResponse
    {
        $search = request()->only('search');
        $permissions = $user->permissions()->latest()->search($search)->get();

        return response()->json($permissions, 200);
    }
    public function store(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grant_date' => 'date',
            'expiration_date' => 'required|date',
            'standard' => 'sometimes|int',
            'form' => 'sometimes|int'
        ]);
        $standard = null;
        $form = null;

        $validated = $this->validateRequest($validated);
        if(!empty($validated['standard'])) {
            $standard = $validated['standard'];
            unset($validated['standard']);
        }
        if(!empty($validated['form'])) {
            $form = $validated['form'];
            unset($validated['form']);
        }
        $permission = Permission::create($validated);
        $syncData = [
            'user_id' => $user->id,
            'permission_id' => $permission->id
        ];
        if($standard) {
            $syncData['standard_id'] = $standard;
        }
        if($form) {
            $syncData['form_id'] = $form;
        }
        DB::table('user_file_permission')->insert($syncData);
        return response()->json([
            'success' => 'Permission Created Successfully!',
            'permission' => $permission
        ], 201);
    }
    public function destroy(User $user, Permission $permission): \Illuminate\Http\JsonResponse
    {
        $realPermission = Permission::where('id', $permission->id)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->firstOrFail();

        $realPermission->delete();
        return response()->json(['success' => 'Permission deleted!'], 200);
    }

    private function validateRequest($validated): array | \Throwable
    {
        if(!isset($validated['standard']) && !isset($validated['form'])) {
            throw new \Exception('Missing required standard', 422);
        }

        return $validated;
    }

    public function getUserPermissions(User $user): \Illuminate\Http\JsonResponse
    {
        $permissions = $user->permissions()->get();

        return response()->json($permissions, 200);
    }
}
