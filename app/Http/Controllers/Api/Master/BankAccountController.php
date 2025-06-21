<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Office_bank_accounts;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankAccountResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    public function index()
    {
        $Office_bank_accounts = Office_bank_accounts::when(request()->search, function ($Office_bank_accounts) {
            $Office_bank_accounts = $Office_bank_accounts->where('bankGroupId', 'like', '%' . request()->search . '%')
                ->orWhere('accountNum', 'like', '%' . request()->search . '%')
                ->orWhere('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $Office_bank_accounts->appends(['search' => request()->search]);

        return new BankAccountResource(true, 'List data Bank Account', $Office_bank_accounts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank' => 'required',
            'accountNum'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $bankaccount = new Office_bank_accounts();
        $bankaccount->name = $request->name ?? 0;
        $bankaccount->bank = $request->bank;
        $bankaccount->no_rek = $request->accountNum;
        $bankaccount->location_id = (is_null($request->location_id) || $request->location_id === '') ? 0 : (int)$request->location_id;
        $bankaccount->description = $request->desc ?? '';
        $bankaccount->is_active = $request->is_active ?? 0;

        $bankaccount->save();

        if($bankaccount)
        {
            return new BankAccountResource(true, 'Data Bank Account Berhasil Disimpan!', $bankaccount);
        }

        return new BankAccountResource(false, 'Data Bank Account Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $bankaccount = Office_bank_accounts::whereId($id)->first();

        if($bankaccount)
        {
            return new BankAccountResource(true, 'Detail Data Bank Account!', $bankaccount);
        }

        return new BankAccountResource(false, 'Detail Data Bank Account Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'bank'  => 'required',
            'accountNum' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bankaccount = Office_bank_accounts::whereId($id)->first();

        $user = auth()->guard('api')->user();

        $bankaccount->name = $request->name ?? 0;
        $bankaccount->bank = $request->bank;
        $bankaccount->no_rek = $request->accountNum;
        $bankaccount->location_id = (is_null($request->location_id) || $request->location_id === '') ? 0 : (int)$request->location_id;;
        $bankaccount->description = $request->description ?? '';
        $bankaccount->is_active = $request->is_active ?? 0;


        $bankaccount->save();


        if($bankaccount)
        {
            return new BankAccountResource(true, 'Data Bank Account Berhasil Diupdate!', $bankaccount);
        }

        return new BankAccountResource(false, 'Data Bank Account Gagal Diupdate!', null);
    }

    public function destroy(Office_bank_accounts $bankaccount)
    {
        if($bankaccount->delete())
        {
            return new BankAccountResource(true, 'Data Bank Account Berhasil Dihapus!', null);
        }

        return new BankAccountResource(false, 'Data Bank Account Gagal Dihapus!', null);
    }

    public function all()
    {
        $Office_bank_accounts = Office_bank_accounts::latest()->get();

        return new BankAccountResource(true, 'List Data Bank Account', $Office_bank_accounts);
    }
}
