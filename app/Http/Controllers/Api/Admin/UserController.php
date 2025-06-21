<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Users;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $subs = Users::when(request()->search, function ($subs) {
            $subs = $subs->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $subs->appends(['search' => request()->search]);

        return new UserResource(true, 'List data subcategory', $subs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'username' => 'required',
            'email' => 'required',
            'company' => 'required',
            'dimention_type' => 'required',
            'location' => 'required',
            'warehouse' => 'required',
            'office_team' => 'required',
            'customer' => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = new Users();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->group = $request->group;
        $user->company_id = $request->company_id;
        $user->dimention_type = $request->dimention_type;
        $user->location_id = $request->location_id;
        $user->warehouse_id = $request->warehouse_id;
        $user->office_team = $request->office_team;
        $user->is_active = $request->is_active;
        $user->save();

        

        if($user)
        {
            return new UserResource(true, 'Data User Berhasil Disimpan!', $user);
        }

        return new UserResource(false, 'Data Subcategory Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $sub = Users::whereId($id)->first();

        if($sub)
        {
            return new UserResource(true, 'Detail Data Subcategory!', $sub);
        }

        return new UserResource(false, 'Detail Data Subcategory Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $sub = Users::whereId($id)->first();

        $sub->name = $request->name;
        $sub->is_active = $request->is_active;

        $sub->save();


        if($sub)
        {
            return new UserResource(true, 'Data Subcategory Berhasil Diupdate!', $sub);
        }

        return new UserResource(false, 'Data Subcategory Gagal Diupdate!', null);
    }

    public function destroy(Users $sub)
    {
        if($sub->delete())
        {
            return new UserResource(true, 'Data Subcategory Berhasil Dihapus!', null);
        }

        return new UserResource(false, 'Data Subcategory Gagal Dihapus!', null);
    }

    public function all()
    {
        $users = Users::latest()->get();

        return new UserResource(true, 'List Data User', $users);
    }
}
