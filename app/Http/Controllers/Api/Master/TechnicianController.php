<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Office_technicians;
use App\Http\Controllers\Controller;
use App\Http\Resources\TechnicianResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        if($user->office_team == 1){
            $techs = Office_technicians::when(request()->search, function ($techs) {
                $techs = $techs->where('name', 'like', '%' . request()->search . '%');
            })->orderBy('code', 'asc')->where('is_active', 1)->where('location_id', $user->location_id)->latest()->paginate(10);
        }

        $techs = Office_technicians::when(request()->search, function ($techs) {
            $techs = $techs->where('name', 'like', '%' . request()->search . '%');
        })->orderBy('code', 'asc')->where('is_active', 1)->latest()->paginate(10);

        $techs->appends(['search' => request()->search]);

        return new TechnicianResource(true, 'List data Technician', $techs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'phone'  => 'required',
            'user_id'  => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $tech = new Office_technicians();
        $tech->code = Office_technicians::getNextCode();
        $tech->name = strtoupper($request->name);
        $tech->phone = $request->phone;
        $tech->user_id = $request->user_id;
        $tech->location_id = $user->location_id;
        $tech->warehouse_id = $user->warehouse_id;
        $tech->company_id = $user->company_id;
        $tech->saved_id = $user->id;
        $tech->is_active = $request->is_active;
        $tech->address = $request->address ?? null;
        $tech->email = $request->email ?? null;
        // dd($tech);
        $tech->save();

        if($tech)
        {
            return new TechnicianResource(true, 'Data Technician Berhasil Disimpan!', $tech);
        }

        return new TechnicianResource(false, 'Data Technician Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $tech = Office_technicians::whereId($id)->first();

        if($tech)
        {
            return new TechnicianResource(true, 'Detail Data Technician!', $tech);
        }

        return new TechnicianResource(false, 'Detail Data Technician Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
            'phone'  => 'required',
            'user_id'  => 'required',
            'location_id'  => 'required',
            'warehouse_id' => 'required',
            'company_id' => 'required',
            'saved_id' => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();
        $tech = Office_technicians::whereId($id)->first();

        $tech->name = strtoupper($request->name);
        $tech->phone = $request->phone;
        $tech->user_id = $request->user_id;
        $tech->location_id = $user->location_id;
        $tech->warehouse_id = $user->warehouse_id;
        $tech->company_id = $user->company_id;
        $tech->updated_id = $user->id;
        $tech->is_active = $request->is_active;

        if(isset($request->address)){
            $tech->address = $request->address;
        }

        if(isset($request->email)){
            $tech->email = $request->email;
        }

        $tech->save();


        if($tech)
        {
            return new TechnicianResource(true, 'Data Technician Berhasil Diupdate!', $tech);
        }

        return new TechnicianResource(false, 'Data Technician Gagal Diupdate!', null);
    }

    public function destroy(Office_technicians $tech)
    {
        if($tech->delete())
        {
            return new TechnicianResource(true, 'Data Technician Berhasil Dihapus!', null);
        }

        return new TechnicianResource(false, 'Data Technician Gagal Dihapus!', null);
    }

    public function all()
    {
        $techs = Office_technicians::latest()->get();

        return new TechnicianResource(true, 'List Data Technician', $techs);
    }
}
