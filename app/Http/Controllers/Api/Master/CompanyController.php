<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Companys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
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
        $companies = Companys::when(request()->search, function ($companies) {
            $companies = $companies->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $companies->appends(['search' => request()->search]);

        return new CompanyResource(true, 'List data company', $companies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $company = new Companys();
        $company->code = $request->code;
        $company->name = $request->name;
        if($request->address){
            $company->address = $request->address;
        }
        if($request->bank){
            $company->bank = $request->bank;
        }
        if($request->phone){
            $company->phone = $request->phone;
        }
        if($request->email){
            $company->email = $request->email;
        }
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $filePath = $file->store('company', 'public');
            $fileType = $file->getClientOriginalExtension();

            $company->file_path = $filePath;
            $company->file_type = $fileType;
        }
        $company->is_active = $request->is_active;
        $company->save();

        if($company)
        {
            return new CompanyResource(true, 'Data Company Berhasil Disimpan!', $company);
        }

        return new CompanyResource(false, 'Data Company Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $company = Companys::whereId($id)->first();

        if($company)
        {
            $company->logoUrl = $company->file_path ? asset('storage/' . $company->file_path) : null;
            return new CompanyResource(true, 'Detail Data Company!', $company);
        }

        return new CompanyResource(false, 'Detail Data Company Tidak Ditemukan!', null);
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

        $companys = Companys::whereId($id)->first();

        // dd($currentFilePath);

        $companys->code = $request->code;
        $companys->name = $request->name;
        if($request->address){
            $companys->address = $request->address;
        }
        if($request->bank){
            $companys->bank = $request->bank;
        }
        if($request->phone){
            $companys->phone = $request->phone;
        }
        if($request->email){
            $companys->email = $request->email;
        }
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $filePath = $file->store('company');
            $fileType = $file->getClientOriginalExtension();

            $companys->file_path = $filePath;
            $companys->file_type = $fileType;
        }

        $companys->is_active = $request->is_active;
        $companys->save();

        if($companys)
        {
            return new CompanyResource(true, 'Data Company Berhasil Diupdate!', $companys);
        }

        return new CompanyResource(false, 'Data Company Gagal Diupdate!', null);
    }

    public function destroy(Companys $companys)
    {
        if($companys->delete())
        {
            return new CompanyResource(true, 'Data Company Berhasil Dihapus!', null);
        }

        return new CompanyResource(false, 'Data Company Gagal Dihapus!', null);
    }

    public function all()
    {
        $companies = Companys::latest()->get();

        return new CompanyResource(true, 'List Data Company', $companies);
    }
}
