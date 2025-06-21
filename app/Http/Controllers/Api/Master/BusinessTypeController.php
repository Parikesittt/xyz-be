<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Business_types;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessTypeResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessTypeController extends Controller
{
    public function index()
    {
        $busType = Business_types::when(request()->search, function ($busType) {
            $busType = $busType->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $busType->appends(['search' => request()->search]);

        return new BusinessTypeResource(true, 'List data business type', $busType);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
            'is_active'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $busType = new Business_types();
        $busType->name = strtoupper($request->name);
        $busType->code = strtoupper($request->code);
        $busType->company_id = $user->company_id;
        $busType->saved_id = $user->id;
        $busType->is_active = $request->is_active;
        $busType->save();

        if($busType)
        {
            return new BusinessTypeResource(true, 'Data Business Type Berhasil Disimpan!', $busType);
        }

        return new BusinessTypeResource(false, 'Data Business Type Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $busType = Business_types::whereId($id)->first();

        if($busType)
        {
            return new BusinessTypeResource(true, 'Detail Data Business Type!', $busType);
        }

        return new BusinessTypeResource(false, 'Detail Data Business Type Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'code' => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $busType = Business_types::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $busType->name = $request->name;
        $busType->code = $request->code;
        $busType->company_id = $user->company_id;
        $busType->updated_id = $user->id;
        $busType->is_active = $request->is_active;

        $busType->save();


        if($busType)
        {
            return new BusinessTypeResource(true, 'Data Business Type Berhasil Diupdate!', $busType);
        }

        return new BusinessTypeResource(false, 'Data Business Type Gagal Diupdate!', null);
    }

    public function destroy(Business_types $busType)
    {
        if($busType->delete())
        {
            return new BusinessTypeResource(true, 'Data Business Type Berhasil Dihapus!', null);
        }

        return new BusinessTypeResource(false, 'Data Business Type Gagal Dihapus!', null);
    }

    public function all()
    {
        $busTypes = Business_types::latest()->get();

        return new BusinessTypeResource(true, 'List Data Business Type', $busTypes);
    }
}
