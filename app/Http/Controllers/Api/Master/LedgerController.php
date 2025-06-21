<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Ledgers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LedgerResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LedgerController extends Controller
{
    public function index()
    {
        $Ledgers = Ledgers::when(request()->search, function ($Ledgers) {
            $Ledgers = $Ledgers->where('account_name', 'like', '%' . request()->search . '%')
            ->orWhere('ledger_account', 'like', '%' . request()->search . '%');
        })->orderBy('id', 'asc')->latest()->paginate(10);

        $Ledgers->appends(['search' => request()->search]);

        return new LedgerResource(true, 'List data Ledgers', $Ledgers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ledger_account'  => 'required',
            'account_name'  => 'required',
            'account_type'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();
        $cek_ledger = Ledgers::where('ledger_account', $request->ledger_account)->first();

        $ledger = new Ledgers();
        if(!$cek_ledger){
            $ledger->ledger_account = $request->ledger_account;
            $ledger->account_name = $request->account_name;
            $ledger->account_type = $request->account_type;
            $ledger->user_name = $user->name;
        }
        $ledger->save();

        if($ledger)
        {
            return new LedgerResource(true, 'Data ledger Berhasil Disimpan!', $ledger);
        }

        return new LedgerResource(false, 'Data Location Gagal Disimpan!', null);
    }

    public function cekAccount($account)
    {
        $data = Ledgers::where('ledger_account', $account)->first();

        return new LedgerResource(true, 'Data ledger', $data);
    }

    public function show($id)
    {
        $location = Ledgers::whereId($id)->first();

        if($location)
        {
            return new LedgerResource(true, 'Detail Data Location!', $location);
        }

        return new LedgerResource(false, 'Detail Data Location Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'ledger_account'  => 'required',
            'account_name'  => 'required',
            'account_type'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $ledger = Ledgers::whereId($id)->first();

        $user = auth()->guard('api')->user();

        $ledger->ledger_account = $request->ledger_account;
        $ledger->account_name = $request->account_name;
        $ledger->account_type = $request->account_type;
        $ledger->user_edit = $user->name;
        $ledger->save();


        if($ledger)
        {
            return new LedgerResource(true, 'Data ledger Berhasil Diupdate!', $ledger);
        }

        return new LedgerResource(false, 'Data ledger Gagal Diupdate!', null);

    }

    public function destroy(Ledgers $location)
    {
        if($location->delete())
        {
            return new LedgerResource(true, 'Data Location Berhasil Dihapus!', null);
        }

        return new LedgerResource(false, 'Data Location Gagal Dihapus!', null);
    }

    public function all()
    {
        $Ledgers = Ledgers::latest()->get();

        return new LedgerResource(true, 'List Data Location', $Ledgers);
    }
}
