<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Customer_departments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerDepartmentsResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerDepartmentsController extends Controller
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
        $customerdepartments = Customer_departments::get();

        return new CustomerDepartmentsResource(true, 'List data customer department', $customerdepartments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $customerdepartment = new Customer_departments();
        $customerdepartment->name = $request->name;
        $customerdepartment->is_active = $request->is_active;

        $customerdepartment->save();

        if($customerdepartment)
        {
            return new CustomerDepartmentsResource(true, 'Data customerdepartment Berhasil Disimpan!', $customerdepartment);
        }

        return new CustomerDepartmentsResource(false, 'Data customerdepartment Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $customerdepartment = Customer_departments::whereId($id)->first();

        if($customerdepartment)
        {
            return new CustomerDepartmentsResource(true, 'Detail Data customerdepartment!', $customerdepartment);
        }

        return new CustomerDepartmentsResource(false, 'Detail Data customerdepartment Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customerdepartment = Customer_departments::whereId($id)->first();

        $customerdepartment->name = $request->name;
        $customerdepartment->is_active = $request->is_active;
        
        $customerdepartment->save();


        if($customerdepartment)
        {
            return new CustomerDepartmentsResource(true, 'Data customerdepartment Berhasil Diupdate!', $customerdepartment);
        }

        return new CustomerDepartmentsResource(false, 'Data customerdepartment Gagal Diupdate!', null);
    }

    public function destroy(Customer_departments $customerdepartment)
    {
        if($customerdepartment->delete())
        {
            return new CustomerDepartmentsResource(true, 'Data customerdepartment Berhasil Dihapus!', null);
        }

        return new CustomerDepartmentsResource(false, 'Data customerdepartment Gagal Dihapus!', null);
    }

    public function all()
    {
        $Customer_departments = Customer_departments::latest()->get();

        return new CustomerDepartmentsResource(true, 'List Data customerdepartments', $Customer_departments);
    }
}
