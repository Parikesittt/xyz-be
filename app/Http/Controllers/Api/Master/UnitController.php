<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Units;
use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index()
    {
        $units = Units::when(request()->search, function ($units) {
            $units = $units->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $units->appends(['search' => request()->search]);

        return new UnitResource(true, 'List data Unit', $units);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit'  => 'required',
            'desc' => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $unit = new Units();
        $unit->unit = $request->unit;
        $unit->desc = $request->desc;
        $unit->is_active = $request->is_active;
        $unit->save();

        if($unit)
        {
            return new UnitResource(true, 'Data Unit Berhasil Disimpan!', $unit);
        }

        return new UnitResource(false, 'Data Unit Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $unit = Units::whereId($id)->first();

        if($unit)
        {
            return new UnitResource(true, 'Detail Data Unit!', $unit);
        }

        return new UnitResource(false, 'Detail Data Unit Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'unit'  => 'required',
            'desc' => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $unit = Units::whereId($id)->first();

        $unit->unit = $request->unit;
        $unit->desc = $request->desc;
        $unit->is_active = $request->is_active;

        $unit->save();


        if($unit)
        {
            return new UnitResource(true, 'Data Unit Berhasil Diupdate!', $unit);
        }

        return new UnitResource(false, 'Data Unit Gagal Diupdate!', null);
    }

    public function destroy(Units $unit)
    {
        if($unit->delete())
        {
            return new UnitResource(true, 'Data Unit Berhasil Dihapus!', null);
        }

        return new UnitResource(false, 'Data Unit Gagal Dihapus!', null);
    }

    public function all()
    {
        $unit = Units::latest()->get();

        return new UnitResource(true, 'List Data Unit', $unit);
    }
}
