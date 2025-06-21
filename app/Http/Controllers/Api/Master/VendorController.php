<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Suppliers;
use App\Models\Vendor_groups;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class VendorController extends Controller
{
    public function index()
    {
        $supps = Suppliers::when(request()->search, function ($supps) {
            $supps = $supps->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $supps->appends(['search' => request()->search]);

        return new VendorResource(true, 'List Data Vendor', $supps);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'accountNum'  => 'required',
            'name' => 'required',
            'currency' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $supp = new Suppliers();
        $supp->accountNum = $request->accountNum;
        $supp->name = $request->name;
        $supp->currency = $request->currency;
        $supp->company_id = $user->company_id;
        $supp->account_type = 'Ledger';
        $supp->address = $request->address ?? '';
        $supp->phone = $request->phone ?? 0;
        $supp->telefax = $request->telefax ?? 0;
        $supp->email = $request->email ?? '';
        $supp->supplier_type = $request->supplier_type ?? 0;
        $supp->terms = $request->terms ?? 0;
        $supp->is_principal = $request->is_principal ?? 0;
        $supp->vend_group = $request->vend_group ?? 0;
        $supp->no_npwp = $request->no_npwp ?? 0;
        $supp->bank = $request->bank ?? '';
        $supp->rek = $request->rek ?? 0;
        $supp->type = $request->type ?? 0;
        $supp->purchase_order_prices = $request->purchase_order_prices ?? 0;
        $supp->location_head = $request->location_head ?? 0;
        $supp->location_department = $request->location_department ?? 0;
        $supp->location_code = $request->location_code ?? 0;
        $supp->is_active = $request->is_active ?? 0;

        $cek_data = Vendor_groups::where('group', $request->vend_group)->first();
        if($cek_data){
            $supp->LedgerAccountId = $cek_data->ledger_account;
            $supp->offsetAccount = $cek_data->offset_account;
        }

        $supp->save();

        if($supp)
        {
            return new VendorResource(true, 'Data Vendor Berhasil Disimpan!', $supp);
        }

        return new VendorResource(false, 'Data Vendor Gagal Disimpan!', null);
    }

    public function vendor_groups(){
        $vendorGroup = Vendor_groups::get();

        return new VendorResource(true, 'Data vendor group', $vendorGroup);
    }

    public function supplier($accountNum){
        $user = auth()->guard('api')->user();
        $supp = DB::table('supplier')
                ->select(
                    'supplier.*',
                    DB::raw('(select percen from item_sales_tax where ppn_active=1 limit 1) as ppn_percen')
                )
                ->where('company_id',$user->company_id)
                ->where('accountNum', $accountNum)
                ->get();

        return new VendorResource(true, 'List Supplier', $supp);
    }

    public function show($id)
    {
        $supp = Suppliers::whereId($id)->first();

        if($supp)
        {
            return new VendorResource(true, 'Detail Data Vendor!', $supp);
        }

        return new VendorResource(false, 'Detail Data Vendor Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'accountNum'  => 'required',
            'name' => 'required',
            'currency' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();
        $supp = Suppliers::whereId($id)->first();

        $supp->accountNum = $request->accountNum;
        $supp->name = $request->name;
        $supp->currency = $request->currency;
        $supp->company_id = $user->company_id;
        $supp->account_type = 'Ledger';
        $supp->address = $request->address ?? '';
        $supp->phone = $request->phone ?? 0;
        $supp->telefax = $request->telefax ?? 0;
        $supp->email = $request->email ?? '';
        $supp->supplier_type = $request->supplier_type ?? 0;
        $supp->terms = $request->terms ?? 0;
        $supp->is_principal = $request->is_principal ?? 0;
        $supp->vend_group = $request->vend_group ?? 0;
        $supp->no_npwp = $request->no_npwp ?? 0;
        $supp->bank = $request->bank ?? '';
        $supp->rek = $request->rek ?? 0;
        $supp->type = $request->type ?? 0;
        $supp->purchase_order_prices = $request->purchase_order_prices ?? 0;
        $supp->location_head = $request->location_head ?? 0;
        $supp->location_department = $request->location_department ?? 0;
        $supp->location_code = $request->location_code ?? 0;
        $supp->is_active = $request->is_active ?? 0;

        $cek_data = Vendor_groups::where('group', $request->vend_group)->first();
        if($cek_data){
            $supp->LedgerAccountId = $cek_data->ledger_account;
            $supp->offsetAccount = $cek_data->offset_account;
        }

        $supp->save();


        if($supp)
        {
            return new VendorResource(true, 'Data Vendor Berhasil Diupdate!', $supp);
        }

        return new VendorResource(false, 'Data Vendor Gagal Diupdate!', null);
    }

    public function destroy(Suppliers $supp)
    {
        if($supp->delete())
        {
            return new VendorResource(true, 'Data Vendor Berhasil Dihapus!', null);
        }

        return new VendorResource(false, 'Data Vendor Gagal Dihapus!', null);
    }

    public function all()
    {
        $supp = Suppliers::latest()->get();

        return new VendorResource(true, 'List Data Vendor', $supp);
    }
}
