<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Customers;
use App\Models\Locations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer_groups;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{

    public function index()
    {
        $user = auth()->guard('api')->user();
        // dd($user);
        if($user->office_team == 1){
            $customers = Customers::when(request()->search, function ($customers) {
                $customers = $customers->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('accountNum', 'like', '%' . request()->search . '%');
            })
            ->where('is_office', 1)
            ->where('company_id', $user->company_id)
            ->where('is_active', 1)
            ->latest()->paginate(10);
        }else{
            $customers = Customers::when(request()->search, function ($customers) {
                $customers = $customers->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('accountNum', 'like', '%' . request()->search . '%');
            })
            ->where('company_id', $user->company_id)
            ->where('is_active', 1)
            ->latest()->paginate(10);
        }

        $customers->appends(['search' => request()->search]);

        return new CustomerResource(true, 'List data Customers', $customers);
    }

    public function cek_location_cust(){
        $user = auth()->guard('api')->user();

        if($user->office_team == 1){
            $location = Locations::when(request()->search, function ($customers) {
                $customers = $customers->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('accountNum', 'like', '%' . request()->search . '%');
            })->where('is_active', 1)->whereId($user->location_id)->get();
        }else{
            $location = Locations::when(request()->search, function ($customers) {
                $customers = $customers->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('accountNum', 'like', '%' . request()->search . '%');
            })->where('is_active', 1)->get();
        }

        if($location){
            return new CustomerResource(true, 'List Location', $location);
        }

        return new CustomerResource(false, 'Location tidak ditemukan', $location);
    }

    public function customer_groups(){
        $data = Customer_groups::get();

        return new CustomerResource(true, 'List Customer Group', $data);
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

        $user = auth()->guard('api')->user();

        $customer = new Customers();

            if($request->is_office == 1){
                $customer->accountNum = Customers::getNextCounterCustomerOfficeId();
            }else{
                $customer->accountNum = $request->accountNum;
            }
            $customer->name = $request->name;
            $customer->company_id = $user->company_id;
            $customer->customer_type = 'Local';
            $customer->currencyCode = $request->currency;
            $customer->user_id = $user->id;
            $customer->invoice_group = $request->invoice_group ?? 0;
            $customer->type_payment = $request->type_payment ?? 0;
            $customer->max_invoice = $request->max_invoice ?? 0;
            if(isset($request->limit)){
                $customer->limit = $request->limit;
                $customer->limit_so = $request->limit;
                $customer->available = $request->limit;
            }else{
                $customer->limit = 0;
                $customer->limit_so = 0;
                $customer->available = 0;
            }
            $customer->limit_invoice = $request->limit_invoice ?? 0;
            $customer->limit_cash = $request->limit_cash ?? 0;
            $customer->npwp = $request->npwp ?? 0;
            $customer->npwp_bill = $request->npwp_bill ?? 0;
            $customer->condition = $request->condition ?? 0;
            $customer->outlet = $request->outlet ?? 0;
            $customer->regular_discount = $request->regular_discount ?? 0;
            $customer->regular_discount_active = $request->regular_discount_active ?? 0;
            $customer->distribution_center_discount = $request->distribution_center_discount ?? 0;
            $customer->distribution_center_discount_active = $request->distribution_center_discount_active ?? 0;
            $customer->promotion_discount = $request->promotion_discount ?? 0;
            $customer->anniversary_discount = $request->anniversary_discount ?? 0;
            $customer->anniversary_discount_active = $request->anniversary_discount_active ?? 0;
            $customer->new_outlet_discount = $request->new_outlet_discount ?? 0;
            $customer->address = $request->address ?? 0;
            $customer->address_bill = $request->address_bill ?? 0;
            $customer->project_address = $request->project_address ?? 0;
            $customer->uniq_name = $request->uniq_name ?? 0;
            $customer->info = $request->info ?? 0;
            $customer->phone = $request->phone ?? 0;
            $customer->telefax = $request->telefax ?? 0;
            $customer->email = $request->email ?? 0;
            $customer->invoice_account = $request->invoice_account ?? 0;
            $customer->terms = $request->terms ?? 0;
            $customer->channel_id = $request->channel_id ?? 0;
            $customer->salesman_id = $request->salesman_id ?? 0;
            $customer->contact = $request->contact ?? 0;
            $customer->business_type_id = $request->business_type_id ?? 0;
            $customer->is_active = $request->is_active ?? 0;
            $customer->is_office = $request->is_office ?? 0;
            $customer->is_not_dp = $request->is_not_dp ?? 0;
            $customer->is_berikat = $request->is_berikat ?? 0;
            $customer->cust_group = $request->cust_group ?? 0;
            $customer->location_head = $request->location_head ?? 0;
            $customer->location_department = $request->location_department ?? 0;
            $customer->location_code = $request->location_code ?? 0;
            $customer->branch = $request->branch ?? 0;
            $customer->type_customer = $request->type_customer ?? 0;
            $customer->head_group = $request->head_group ?? 0;
            $customer->department = $request->department ?? 0;
            $customer->area = $request->area ?? 0;
            $customer->market = $request->market ?? 0;
            $customer->target_penjualan = $request->target_penjualan ?? 0;
            $customer->jaminan_termurah = $request->jaminan_termurah ?? 0;
            $customer->potongan_harga_tetap = $request->potongan_harga_tetap ?? 0;
            $customer->reguler_awal = $request->reguler_awal ?? 0;
            $customer->reguler_tambahan = $request->reguler_tambahan ?? 0;
            $customer->admin_barang_baru = $request->admin_barang_baru ?? 0;
            $customer->admin_pengganti_distributor = $request->admin_pengganti_distributor ?? 0;
            $customer->promosi_reguler = $request->promosi_reguler ?? 0;
            $customer->promosi_clearance = $request->promosi_clearance ?? 0;
            $customer->promosi_acara_khusus = $request->promosi_acara_khusus ?? 0;
            $customer->promosi_barang_baru = $request->promosi_barang_baru ?? 0;
            $customer->promosi_diskon_kartu = $request->promosi_diskon_kartu ?? 0;
            $customer->biaya_promosi = $request->biaya_promosi ?? 0;
            $customer->biaya_jaminan = $request->biaya_jaminan ?? 0;
            $customer->biaya_sewa = $request->biaya_sewa ?? 0;
            $customer->biaya_barang_baru = $request->biaya_barang_baru ?? 0;
            $customer->lain_distribusi = $request->lain_distribusi ?? 0;
            $customer->lain_barang_rusak = $request->lain_barang_rusak ?? 0;
            $customer->lain_harga_nasional = $request->lain_harga_nasional ?? 0;
            $customer->lain_harga_khusus = $request->lain_harga_khusus ?? 0;
            $customer->lain_service_level = $request->lain_service_level ?? 0;
            $customer->lain_denda_pengiriman = $request->lain_denda_pengiriman ?? 0;
            $customer->dukungan_sampling = $request->dukungan_sampling ?? 0;
            $customer->dukungan_hadiah = $request->dukungan_hadiah ?? 0;
            $customer->dukungan_partisipasi_pembukaan = $request->dukungan_partisipasi_pembukaan ?? 0;
            $customer->dukungan_harga_pembukaan = $request->dukungan_harga_pembukaan ?? 0;
            $customer->dukungan_partisipasi_ulangtahun = $request->dukungan_partisipasi_ulangtahun ?? 0;
            $customer->dukungan_denda_barang = $request->dukungan_denda_barang ?? 0;

            $cek_data = Customer_groups::where('group', $request->cust_group)->first();
            if($cek_data){
                $customer->LedgerAccountId = $cek_data->ledger_account;
                $customer->offsetAccount = $cek_data->offset_account;
            }

            $customer->save();

        if($customer)
        {
            return new CustomerResource(true, 'Data customer Berhasil Disimpan!', $customer);
        }

        return new CustomerResource(false, 'Data Branch Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $branch = Customers::whereId($id)->first();

        if($branch)
        {
            return new CustomerResource(true, 'Detail Data Branch!', $branch);
        }

        return new CustomerResource(false, 'Detail Data Branch Tidak Ditemukan!', null);
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

        $branch = Customers::whereId($id)->first();

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
            return new CustomerResource(true, 'Data Branch Berhasil Diupdate!', $branch);
        }

        return new CustomerResource(false, 'Data Branch Gagal Diupdate!', null);
    }

    public function destroy(Customers $branch)
    {
        if($branch->delete())
        {
            return new CustomerResource(true, 'Data Branch Berhasil Dihapus!', null);
        }

        return new CustomerResource(false, 'Data Branch Gagal Dihapus!', null);
    }

    public function all()
    {
        $Customers = Customers::latest()->get();

        return new CustomerResource(true, 'List Data Branchs', $Customers);
    }
}
