<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Financial_statements;
use App\Http\Controllers\Controller;
use App\Http\Resources\FinancialStatementResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinancialStatementController extends Controller
{
    public function index()
    {
        $fs = Financial_statements::when(request()->search, function ($fs) {
            $fs = $fs->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $fs->appends(['search' => request()->search]);

        return new FinancialStatementResource(true, 'List data Facility', $fs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
            'type'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $fs = new Financial_statements();
        $fs->code = strtoupper($request->code);
        $fs->name = strtoupper($request->name);
        $fs->type = $request->type;
        $fs->is_active = $request->is_active;
        $fs->save();

        if($fs)
        {
            return new FinancialStatementResource(true, 'Data fs Berhasil Disimpan!', $fs);
        }

        return new FinancialStatementResource(false, 'Data Facility Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $facility = Financial_statements::whereId($id)->first();

        if($facility)
        {
            return new FinancialStatementResource(true, 'Detail Data Facility!', $facility);
        }

        return new FinancialStatementResource(false, 'Detail Data Facility Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
            'type'  => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fs = Financial_statements::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $fs->code = strtoupper($request->code);
        $fs->name = strtoupper($request->name);
        $fs->type = $request->type;
        $fs->is_active = $request->is_active;
        $fs->save();


        if($fs)
        {
            return new FinancialStatementResource(true, 'Data fs Berhasil Diupdate!', $fs);
        }

        return new FinancialStatementResource(false, 'Data Financial Statement Gagal Diupdate!', null);
    }

    public function destroy(Financial_statements $fs)
    {
        if($fs->delete())
        {
            return new FinancialStatementResource(true, 'Data Financial Statement Berhasil Dihapus!', null);
        }

        return new FinancialStatementResource(false, 'Data Financial Statement Gagal Dihapus!', null);
    }

    public function all()
    {
        $fs = Financial_statements::latest()->get();

        return new FinancialStatementResource(true, 'List Data Financial Statement', $fs);
    }
}
