<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Office_branchs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
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
        $branchs = Office_branchs::when(request()->search, function ($branchs) {
            $branchs = $branchs->where('name', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%');
        })->with('location')->latest()->paginate(10);

        $branchs->appends(['search' => request()->search]);

        return new BranchResource(true, 'List data Branchs', $branchs);
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

        $branch = new Office_branchs();
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
            return new BranchResource(true, 'Data Branch Berhasil Disimpan!', $branch);
        }

        return new BranchResource(false, 'Data Branch Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $branch = Office_branchs::whereId($id)->first();

        if($branch)
        {
            return new BranchResource(true, 'Detail Data Branch!', $branch);
        }

        return new BranchResource(false, 'Detail Data Branch Tidak Ditemukan!', null);
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

        $branch = Office_branchs::whereId($id)->first();

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
            return new BranchResource(true, 'Data Branch Berhasil Diupdate!', $branch);
        }

        return new BranchResource(false, 'Data Branch Gagal Diupdate!', null);
    }

    public function destroy(Office_branchs $branch)
    {
        if($branch->delete())
        {
            return new BranchResource(true, 'Data Branch Berhasil Dihapus!', null);
        }

        return new BranchResource(false, 'Data Branch Gagal Dihapus!', null);
    }

    public function all()
    {
        $Office_branchs = Office_branchs::latest()->get();

        return new BranchResource(true, 'List Data Branchs', $Office_branchs);
    }
}
