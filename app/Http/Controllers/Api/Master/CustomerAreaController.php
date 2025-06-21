<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Customer_areas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerAreaResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerAreaController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('can:company.index', ['only' => ['index']]);
    //     $this->middleware('can:company.create', ['only' => ['store']]);
    //     $this->middleware('can:company.edit', ['only' => ['show, update']]);
    //     $this->middleware('can:company.delete', ['only' => ['destroy']]);
    // }

    public function index()
    {
        $customerarea = Customer_areas::get();

        return new CustomerAreaResource(true, 'List data customer area', $customerarea);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $customerarea = new Customer_areas();
        $customerarea->code = strtoupper($request->code);
        $customerarea->name = strtoupper($request->name);
        $customerarea->is_active = $request->is_active;
        $customerarea->save();

        if($customerarea)
        {
            return new CustomerAreaResource(true, 'Data Customer Area Berhasil Disimpan!', $customerarea);
        }

        return new CustomerAreaResource(false, 'Data Customer Area Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $customerarea = Customer_areas::whereId($id)->first();

        if($customerarea)
        {
            return new CustomerAreaResource(true, 'Detail Data Customer Area!', $customerarea);
        }

        return new CustomerAreaResource(false, 'Detail Data Customer Area Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customerarea = Customer_areas::whereId($id)->first();

        $customerarea->code = strtoupper($request->code);
        $customerarea->name = strtoupper($request->name);
        $customerarea->is_active = $request->is_active;
        $customerarea->save();


        if($customerarea)
        {
            return new CustomerAreaResource(true, 'Data Customer Area Berhasil Diupdate!', $customerarea);
        }

        return new CustomerAreaResource(false, 'Data Customer Area Gagal Diupdate!', null);
    }

    public function destroy(Customer_areas $customerarea)
    {
        if($customerarea->delete())
        {
            return new CustomerAreaResource(true, 'Data Customer Area Berhasil Dihapus!', null);
        }

        return new CustomerAreaResource(false, 'Data Customer Area Gagal Dihapus!', null);
    }

    public function all()
    {
        $Customer_areas = Customer_areas::latest()->get();

        return new CustomerAreaResource(true, 'List Data Customer Area', $Customer_areas);
    }
}
