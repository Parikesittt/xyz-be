<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PDFGenerator\Services\PDFService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{User_apps, Users, Companys, Office_cs_items, Locations, Office_bank_accounts, Office_branchs};
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{
    public function makeCustomerServicePart($id)
    {
        // // Ambil data terkait customer service
        // $user_group_id = null;
        // $client_id = request()->cookie('client_id');
        // $client_token = request()->cookie('client_token');
        // $user_app = User_apps::find($client_id);

        // if ($user_app) {
        //     $user = Users::find($user_app->user_id);
        //     if ($user) {
        //         $user_group_id = intval($user->user_group_id);
        //     }
        // }

        $user = auth()->guard('api')->user();

        $company = Companys::where('id', $user->company_id)->first();
        $datas = Office_cs_items::selectRaw('office_cs_item.*, office_cs.*,
                        round(office_cs_item.cost) as cost,
                        round(office_cs_item.cost_onsite) as cost_onsite,
                        office_cs.code as cs_code,
                        office_customer_item.item_code,
                        office_customer_item.item_name,
                        office_customer_item.item_unit,
                        office_customer_item.product_code,
                        office_customer_item.serial_number,
                        office_customer_item.date_warranty,
                        (select customer.name from customer where id=office_cs.customer_id) as customer_name,
                        (select customer.address from customer where id=office_cs.customer_id) as customer_address,
                        (select customer.phone from customer where id=office_cs.customer_id) as phone,
                        (select customer.email from customer where id=office_cs.customer_id) as email,
                        (select customer.is_not_dp from customer where id=office_cs.customer_id) as is_not_dp,
                        (select brand.name from brand where id=item.brand_id) as brand,
                        (select itemcategory.name from itemcategory where id=item.itemcategory_id) as category,
                        (select subcategory.name from subcategory where id=item.subcategory_id) as type,
                        item.name as model,
                        office_part.is_active as part_active,
                        round(office_part.price) as price,
                        office_part.qty,
                        office_part.part_name,
                        office_part.status_stock')
            ->join('office_cs', 'office_cs_item.cs_id', '=', 'office_cs.id')
            ->join('office_customer_item', 'office_cs_item.item_id', '=', 'office_customer_item.id')
            ->leftJoin('office_part', 'office_cs_item.id', '=', 'office_part.cs_item_id')
            ->join('item', 'office_customer_item.item_code', '=', 'item.code')
            ->where('office_cs_item.id', $id)
            ->get();

        // Format tanggal dan data
        Carbon::setLocale('id');
        $data = $datas[0]->toArray();

        $date_create = Carbon::createFromFormat('Y-m-d', $data['date_create'])->format('d M Y');
        $date_warranty = $data['date_warranty'] ? Carbon::createFromFormat('Y-m-d', $data['date_warranty'])->format('d M Y') : '';
        $saved = Users::whereId($data['saved_id'])->select('name')->get();
        // dd($saved);

        // Formatting incoming source and service type
        $data['incoming_source'] = $this->formatSourceType($data['incoming_source']);
        $data['service_type'] = $this->formatServiceType($data['service_type']);

        // Menyiapkan data yang akan dikirim ke view Blade
        $company_logo = asset($company->file_path); // URL path logo company
        $total_all = 0;
        foreach ($datas as $item) {
            if (($item->part_active ?? 0) < 9) {
                $total_all += ($item->price ?? 0) * ($item->qty ?? 0);
            }
        }

        $service_cost = $data['cost'] ?? 0;
        $onsite_cost = $data['cost_onsite'] ?? 0;
        $ppn_percent = $data['ppn_percen'] ?? 0;
        $is_ppn = $data['is_ppn'] ?? 0;
        $is_berikat = $data['is_berikat'] ?? 0;
        $incoming_source = $data['incoming_source'] ?? '';

        if ($incoming_source === "ON-SITE") {
            $subtotal = $total_all + $service_cost + $onsite_cost;
            if ($is_ppn > 0) {
                if ($is_berikat > 0) {
                    $tax_base = $service_cost + $onsite_cost;
                } else {
                    $tax_base = $subtotal;
                }
                $tax = $tax_base * $ppn_percent / 100;
            } else {
                $tax = 0;
            }
        } else {
            $subtotal = $total_all + $service_cost;
            if ($is_ppn > 0) {
                if ($is_berikat > 0) {
                    $tax_base = $service_cost;
                } else {
                    $tax_base = $subtotal;
                }
                $tax = $tax_base * $ppn_percent / 100;
            } else {
                $tax = 0;
            }
        }

        $total_all_final = $subtotal + $tax;

        if ($is_ppn > 0) {
            $total_for_dp = $total_all_final;
        } else {
            $total_for_dp = $subtotal;
        }
        $dp_required = $total_for_dp > 1000000;
        $dp_amount = $dp_required ? ($total_for_dp * 0.5) : 0;


        // Menambahkan pengecekan lokasi untuk mengambil data akun bank
        $cek_location = Locations::find($data['location_id']);
        if ($cek_location && $cek_location->is_branch > 0) {
            $bank_account = Office_bank_accounts::where('active', 1)
                ->where('location_id', $data['location_id'])
                ->first();
        } else {
            $bank_account = Office_bank_accounts::where('active', 1)
                ->where('location_id', 0)
                ->first();
        }

        $qr_code_value = str_replace("/", "", $data['cs_code']);
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->generate($qr_code_value));

        $customer_service_name = $saved[0]->name ?? '-';
        $website = 'www.xyzgoprint.com';
        $is_branch = $cek_lok->is_branch ?? 0;

        if ($is_branch > 0) {
            $branch = Office_branchs::where('location_id', $data['location_id'])->first();
            $branch_address = $branch->address . ',  Telp: ' . $branch->phone;
            $bank_info = 'No. Rek : (' . $bank_account->bank . ') ' . $bank_account->no_rek;
            $footer_list = [
                $branch_address,
                $bank_info
            ];
        } else {
            $footer_list = [
                'Bekasi Cyber Park Mall Lt. 1 Blok A8 No. 6, Bekasi Barat, Jawa Barat,  Telp: 021-8849-084',
                'Ruko Kalimas Blok B No.17 , Jl Chairil Anwar,  Margahayu  Bekasi Timur , Telp: 021-8835-4971',
                'No. Rek : (' . $bank_account->bank . ') ' . $bank_account->no_rek
            ];
        }


        // Generate PDF using the view
        $pdf = PDF::loadView('pdf.customer_service', compact('datas', 'data', 'company_logo', 'total_all', 'subtotal', 'tax', 'total_all_final',
            'service_cost', 'onsite_cost', 'date_create', 'date_warranty', 'bank_account', 'is_ppn', 'is_berikat', 'dp_required', 'dp_amount', 'bank_account', 'qrcode', 'customer_service_name', 'website', 'footer_list'));

        // Return PDF Stream (output PDF to browser)
        return $pdf->stream('Customer_Service.pdf');
    }

    public function makeCustomerService($id)
    {
        $data = Office_cs_items::selectRaw(
                'office_cs_item.*,
                office_cs_item.cost as cost_service,
                office_cs.*,
                office_cs.rma_number as rma_num,
                customer.*,
                office_customer_item.*,
                office_cs.code as cs_code,
                brand.name as brand,
                itemcategory.name as category,
                subcategory.name as type,
                item.name as model,
                office_technician.name as technician_name,
                customer.name as customer_name,
                company.name as company'
            )
            ->leftJoin('office_cs', 'office_cs_item.cs_id', '=', 'office_cs.id')
            ->leftJoin('office_technician', 'office_cs_item.technician_id', '=', 'office_technician.id')
            ->leftJoin('customer', 'office_cs.customer_id', '=', 'customer.id')
            ->leftJoin('company', 'office_cs.company_id', '=', 'company.id')
            ->leftJoin('office_customer_item', 'office_cs_item.item_id', '=', 'office_customer_item.id')
            ->leftJoin('item','office_customer_item.item_code','=','item.code')
            ->leftJoin('brand','brand.id','=','item.brand_id')
            ->leftJoin('itemcategory','itemcategory.id','=','item.itemcategory_id')
            ->leftJoin('subcategory','subcategory.id','=','item.subcategory_id')
            ->where('office_cs_item.id', $id)
            ->get();

        $data = $data[0]->toArray();
        $saved = Users::whereId($data['saved_id'])->select('name')->get();

        $date_create = Carbon::createFromFormat('Y-m-d', $data['date_create'])->format('d M Y');
        $date_warranty = $data['date_warranty'] ? Carbon::createFromFormat('Y-m-d', $data['date_warranty'])->format('d M Y') : '';

        $data['incoming_source'] = $this->formatSourceType($data['incoming_source']);
        $data['service_type'] = $this->formatServiceType($data['service_type']);

        $data['warranty'] = $this->formatWarranty($data['warranty']);

        $pdf = PDF::loadView('pdf.makeCustomerServicePdf', compact('data', 'saved', 'date_create', 'date_warranty'));

        // Return PDF Stream (output PDF to browser)
        return $pdf->stream('Customer_Service.pdf');
    }

    private function formatSourceType($type)
    {
        if ($type == 1) return "WALK-IN";
        if ($type == 2) return "PICK UP";
        return "ON-SITE";
    }

    private function formatServiceType($type)
    {
        if ($type == 1) return "SERVICE";
        if ($type == 2) return "REPAIR";
        return "SERVICE & REPAIR";
    }

    private function formatWarranty($type)
    {
        if($type == 0) return "NO";
        return "YES";
    }
}
