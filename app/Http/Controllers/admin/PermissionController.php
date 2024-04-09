<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Model\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
     public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $permission = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        return response()->json($permission, 201);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $permission->update([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        return response()->json($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        $permission->delete();

        return response()->json(null, 204);
    }
}
