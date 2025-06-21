<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Currencys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{

    public function index()
    {
        $currency = Currencys::latest()->get();

        return new CurrencyResource(true, 'List data Currency', $currency);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id'  => 'required',
            'name'  => 'required',
            'description'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user()->only(['id', 'company_id']);

        $branch = new Currencys();
        $branch->name = $request->name;
        $branch->description = $request->description;
        $branch->address = $request->address;
        $branch->phone = $request->phone;
        $branch->email = $request->email;
        $branch->location_id = $request->location_id;
        $branch->company_id = $user['company_id'];
        $branch->is_active = $request->is_active;
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $filePath = $file->store('branch');
            $fileType = $file->getClientOriginalExtension();

            $branch->file_path = $filePath;
            $branch->file_type = $fileType;
        }
        $branch->save();

        if($branch)
        {
            return new CurrencyResource(true, 'Data Branch Berhasil Disimpan!', $branch);
        }

        return new CurrencyResource(false, 'Data Branch Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $branch = Currencys::whereId($id)->first();

        if($branch)
        {
            return new CurrencyResource(true, 'Detail Data Branch!', $branch);
        }

        return new CurrencyResource(false, 'Detail Data Branch Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'location_id'  => 'required',
            'name'  => 'required',
            'description'  => 'required'
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $branch = Currencys::whereId($id)->first();

        $user = auth()->guard('api')->user()->only(['id', 'company_id']);

        $branch->name = $request->name;
        $branch->description = $request->description;
        $branch->address = $request->address;
        $branch->phone = $request->phone;
        $branch->email = $request->email;
        $branch->location_id = $request->location_id;
        $branch->company_id = $user['company_id'];
        $branch->is_active = $request->is_active;
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $filePath = $file->store('branch');
            $fileType = $file->getClientOriginalExtension();

            $branch->file_path = $filePath;
            $branch->file_type = $fileType;
        }
        $branch->save();


        if($branch)
        {
            return new CurrencyResource(true, 'Data Branch Berhasil Diupdate!', $branch);
        }

        return new CurrencyResource(false, 'Data Branch Gagal Diupdate!', null);
    }

    public function destroy(Currencys $branch)
    {
        if($branch->delete())
        {
            return new CurrencyResource(true, 'Data Branch Berhasil Dihapus!', null);
        }

        return new CurrencyResource(false, 'Data Branch Gagal Dihapus!', null);
    }

    public function all()
    {
        $Currencys = Currencys::latest()->get();

        return new CurrencyResource(true, 'List Data Currency', $Currencys);
    }
}
