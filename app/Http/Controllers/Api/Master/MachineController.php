<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Machines;
use App\Http\Controllers\Controller;
use App\Http\Resources\MachineResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machines::when(request()->search, function ($machines) {
            $machines = $machines->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $machines->appends(['search' => request()->search]);

        return new MachineResource(true, 'List data business type', $machines);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_code'  => 'required',
            'name'  => 'required',
            'unit' => 'required',
            'is_active'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $machine = new Machines();
        $machine->name = $request->name;
        $machine->item_code = $request->item_code;
        $machine->unit = $request->unit;
        $machine->saved_id = $user->id;
        $machine->is_active = $request->is_active;
        $machine->save();

        if($machine)
        {
            return new MachineResource(true, 'Data Business Type Berhasil Disimpan!', $machine);
        }

        return new MachineResource(false, 'Data Business Type Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $busType = Machines::whereId($id)->first();

        if($busType)
        {
            return new MachineResource(true, 'Detail Data Business Type!', $busType);
        }

        return new MachineResource(false, 'Detail Data Business Type Tidak Ditemukan!', null);
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

        $busType = Machines::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $busType->name = $request->name;
        $busType->code = $request->code;
        $busType->company_id = $user->company_id;
        $busType->updated_id = $user->id;
        $busType->is_active = $request->is_active;

        $busType->save();


        if($busType)
        {
            return new MachineResource(true, 'Data Business Type Berhasil Diupdate!', $busType);
        }

        return new MachineResource(false, 'Data Business Type Gagal Diupdate!', null);
    }

    public function destroy(Machines $busType)
    {
        if($busType->delete())
        {
            return new MachineResource(true, 'Data Business Type Berhasil Dihapus!', null);
        }

        return new MachineResource(false, 'Data Business Type Gagal Dihapus!', null);
    }

    public function all()
    {
        $busTypes = Machines::latest()->get();

        return new MachineResource(true, 'List Data Business Type', $busTypes);
    }
}
