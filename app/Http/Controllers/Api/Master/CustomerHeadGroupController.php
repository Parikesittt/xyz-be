<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Customer_head_groups;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerHeadGroupResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerHeadGroupController extends Controller
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
        $Customer_head_groups = Customer_head_groups::when(request()->search, function ($Customer_head_groups) {
            $Customer_head_groups = $Customer_head_groups->where('name', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%');
        })->with('location')->latest()->paginate(10);

        $Customer_head_groups->appends(['search' => request()->search]);

        return new CustomerHeadGroupResource(true, 'List data Customer head groups', $Customer_head_groups);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $customerHeadGroup = new Customer_head_groups();
        $customerHeadGroup->code = strtoupper($request->code);
        $customerHeadGroup->name = strtoupper($request->name);
        $customerHeadGroup->is_active = $request->is_active;

        $customerHeadGroup->save();

        if($customerHeadGroup)
        {
            return new CustomerHeadGroupResource(true, 'Data customerHeadGroup Berhasil Disimpan!', $customerHeadGroup);
        }

        return new CustomerHeadGroupResource(false, 'Data Customer Head Group Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $customerHeadGroup = Customer_head_groups::whereId($id)->first();

        if($customerHeadGroup)
        {
            return new CustomerHeadGroupResource(true, 'Detail Data Customer Head Group!', $customerHeadGroup);
        }

        return new CustomerHeadGroupResource(false, 'Detail Data Customer Head Group Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customerHeadGroup = Customer_head_groups::whereId($id)->first();

        $customerHeadGroup->code = strtoupper($request->code);
        $customerHeadGroup->name = strtoupper($request->name);
        $customerHeadGroup->is_active = $request->is_active;
        $customerHeadGroup->save();


        if($customerHeadGroup)
        {
            return new CustomerHeadGroupResource(true, 'Data Customer Head Group Berhasil Diupdate!', $customerHeadGroup);
        }

        return new CustomerHeadGroupResource(false, 'Data Customer Head Group Gagal Diupdate!', null);
    }

    public function destroy(Customer_head_groups $customerHeadGroup)
    {
        if($customerHeadGroup->delete())
        {
            return new CustomerHeadGroupResource(true, 'Data Customer Head Group Berhasil Dihapus!', null);
        }

        return new CustomerHeadGroupResource(false, 'Data Customer Head Group Gagal Dihapus!', null);
    }

    public function all()
    {
        $Customer_head_groups = Customer_head_groups::latest()->get();

        return new CustomerHeadGroupResource(true, 'List Data cusot$Customer_head_groups', $Customer_head_groups);
    }
}
