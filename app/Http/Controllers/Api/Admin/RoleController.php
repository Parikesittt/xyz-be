<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })->with('permissions')->latest()->paginate(10);
        $roles->appends(['search' => request()->search]);
        return new RoleResource(true, 'List Data Role', $roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'name' => $request->name,
        ]);

        $role->givePermissionTo($request->permissions);
        Log::info('Assigned permissions:', $role->permissions->pluck('name')->toArray());

        if($role){
            return new RoleResource(true, 'Role Created Successfully', $role);
        }

        return new RoleResource(false, 'Role Creation Failed', null);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        if ($role) {
            return new RoleResource(true, 'Role Found', $role);
        }

        return new RoleResource(false, 'Role Not Found', null);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role->update([
            'name' => $request->name,
        ]);


        $role->syncPermissions($request->permissions);
        // dd($role);
        Log::info('Assigned permissions:', $role->permissions->pluck('name')->toArray());

        if ($role) {
            return new RoleResource(true, 'Role Updated Successfully', $role);
        }

        return new RoleResource(false, 'Role Update Failed', null);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if($role->delete()){
            return new RoleResource(true, 'Role Deleted Successfully', null);
        }

        return new RoleResource(false, 'Role Deletion Failed', null);
    }

    public function all()
    {
        $roles = Role::latest()->get();

        return new RoleResource(true, 'List All Roles', $roles);
    }
}
