<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Facilitys;
use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facilitys::when(request()->search, function ($facilities) {
            $facilities = $facilities->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $facilities->appends(['search' => request()->search]);

        return new FacilityResource(true, 'List data Facility', $facilities);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description'  => 'required',
            'type_facility'  => 'required',
            'value'  => 'required',
            'is_active'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $facility = new Facilitys();
        $facility->description = strtoupper($request->description);
        $facility->type_facility = strtoupper($request->type_facility);
        $facility->value = strtoupper($request->value);
        $facility->company_id = $user->company_id;
        $facility->saved_id = $user->id;
        $facility->is_active = $request->is_active;
        $facility->code = Facilitys::getNextCounterId();
        $facility->save();

        if($facility)
        {
            return new FacilityResource(true, 'Data Facility Berhasil Disimpan!', $facility);
        }

        return new FacilityResource(false, 'Data Facility Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $facility = Facilitys::whereId($id)->first();

        if($facility)
        {
            return new FacilityResource(true, 'Detail Data Facility!', $facility);
        }

        return new FacilityResource(false, 'Detail Data Facility Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'description'  => 'required',
            'type_facility'  => 'required',
            'value'  => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $facility = Facilitys::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $facility->description = strtoupper($request->description);
        $facility->type_facility = strtoupper($request->type_facility);
        $facility->value = strtoupper($request->value);
        $facility->company_id = $user->company_id;
        $facility->updated_id = $user->id;
        $facility->is_active = $request->is_active;

        $facility->save();


        if($facility)
        {
            return new FacilityResource(true, 'Data Facility Berhasil Diupdate!', $facility);
        }

        return new FacilityResource(false, 'Data Facility Gagal Diupdate!', null);
    }

    public function destroy(Facilitys $facility)
    {
        if($facility->delete())
        {
            return new FacilityResource(true, 'Data Facility Berhasil Dihapus!', null);
        }

        return new FacilityResource(false, 'Data Facility Gagal Dihapus!', null);
    }

    public function all()
    {
        $facilities = Facilitys::latest()->get();

        return new FacilityResource(true, 'List Data Facility', $facilities);
    }
}
