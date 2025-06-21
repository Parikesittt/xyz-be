<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $page = request()->get('page', 1);

        $permissions = Permission::when(request()->search, function($permissions) {
            $permissions = $permissions->where('name', 'like', '%'. request()->search . '%');
        })->latest()->paginate(10);

         if ($permissions->isEmpty() && $page > 1) {
            // Redirect or return page 1 results
            $permissions = Permission::when(request()->search, function($query) {
                $query->where('name', 'like', '%' . request()->search . '%');
            })->latest()->paginate(10, ['*'], 'page', 1);
        }

        $permissions->appends(['search' => request()->search]);

        return new PermissionResource(true, 'List Data Permission', $permissions);
    }

    public function all()
    {
        $permissions = Permission::latest()->get();

        return new PermissionResource(true, 'List Data Permission', $permissions);
    }
}
