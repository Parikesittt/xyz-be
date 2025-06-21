<?php

namespace App\Http\Controllers\Api;

use App\Models\Office_css;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeCsResource;
use App\Models\Auth_token_qontak_whatsapps;
use App\Models\Customers;
use App\Models\Companys;
use App\Models\Cust_free_invoices;
use App\Models\Cust_free_invoice_items;
use App\Models\Inventory_postings;
use App\Models\Inventory_temps;
use App\Models\Inventorys;
use App\Models\Iptn_items;
use App\Models\Iptns;
use App\Models\Item_sales_taxs;
use App\Models\Items;
use App\Models\Office_cs_items;
use App\Models\Office_customer_items;
use App\Models\Office_cost_items;
use App\Models\Office_parts;
use App\Models\Office_technicians;
use App\Models\Office_reset_items;
use App\Models\Stocks;
use App\Models\Transaction_temps;
use App\Models\Warehouses;
use App\Models\Transactions;
use App\Models\Ledger_transactions;
use App\Models\Salesinvoices;
use App\Services\Mailer;
use App\Http\Controllers\PDFController;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class OfficeCsController extends Controller
{
    protected $pdfController;
    protected $mailer;

    public function __construct(PDFController $pdfController, Mailer $mailer)
    {
        $this->pdfController = $pdfController;
        $this->mailer = $mailer;
    }

    public function index()
    {
        $user = auth()->guard('api')->user();
        if($user->office_team == 1){
            $subQueryA = DB::table('office_cs')
                ->select(
                    'office_cs.*',
                    'office_cs.id as cs_id',
                    'customer.accountNum as customer_code',
                    'customer.name as customer_name',
                    'customer.email',
                    'customer.phone',
                    DB::raw('(select name from users where id = office_cs.saved_id) as cs_name')
                )
                ->leftJoin('customer', 'office_cs.customer_id', '=', 'customer.id')
                ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
                ->groupBy(
                    'office_cs.id',
                    'office_cs_item.cs_id',
                    'office_cs_item.id',
                    'office_cs_item.is_active',
                    'office_cs_item.item_id',
                    'office_cs_item.type',
                    'office_cs_item.approval_cust',
                    'office_cs_item.warranty',
                    'office_cs_item.incoming_source',
                    'office_cs_item.req_part',
                    'office_cs_item.req_part_to_whs',
                    'office_customer_item.item_code',
                    'office_customer_item.serial_number',
                    'customer.accountNum',
                    'customer.name',
                    'customer.phone',
                    'users.name'
                );

            // Subquery kedua (office_technicians)
            $subQueryTechnicians = DB::table('office_technician')
                ->join('office_cs_item', 'office_cs_item.technician_id', '=', 'office_technician.id')
                ->join('office_customer_item', 'office_cs_item.item_id', '=', 'office_customer_item.id')
                ->select(
                    'office_cs_item.cs_id',
                    DB::raw('date(office_cs_item.created_at) as date_input'),
                    DB::raw('date(office_cs_item.time_process) as time_process'),
                    DB::raw('date(office_cs_item.time_done) as time_done'),
                    DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                    DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                    DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty')
                )
                ->groupBy('office_cs_item.cs_id');


            $data = DB::table(DB::raw('(' . $subQueryA->toSql() . ') as office_css'))
                ->mergeBindings($subQueryA) // Menggabungkan binding dari subquery pertama
                ->select('office_css.*', 'office_technicians.*')
                ->leftJoin(DB::raw('(' . $subQueryTechnicians->toSql() . ') as office_technicians'), function ($join) {
                    $join->on('office_css.cs_id', '=', 'office_technicians.cs_id');
                })
                ->mergeBindings($subQueryTechnicians)
                ->when(request()->search, function ($data) {
                    $data = $data->where('bankGroupId', 'like', '%' . request()->search . '%')
                        ->orWhere('accountNum', 'like', '%' . request()->search . '%')
                        ->orWhere('name', 'like', '%' . request()->search . '%');
                })->latest()->paginate(10);
        } else if($user->office_team == 2){
                        $subQueryA = DB::table('office_cs')
                ->select(
                    'office_cs.*',
                    'office_cs.id as cs_id',
                    'customer.accountNum as customer_code',
                )
                ->leftJoin('customer', 'office_cs.customer_id', '=', 'customer.id');

            // Subquery kedua (office_technicians)
            $subQueryTechnicians = DB::table('office_technician')
                ->join('office_cs_item', 'office_cs_item.technician_id', '=', 'office_technician.id')
                ->join('office_customer_item', 'office_cs_item.item_id', '=', 'office_customer_item.id')
                ->select(
                    'office_cs_item.cs_id',
                    DB::raw('date(office_cs_item.time_process) as time_process'),
                    DB::raw('date(office_cs_item.time_done) as time_done'),
                    DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                    DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                    DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty')
                )
                ->groupBy('office_cs_item.cs_id');


            $data = DB::table(DB::raw('(' . $subQueryA->toSql() . ') as office_css'))
                ->mergeBindings($subQueryA) // Menggabungkan binding dari subquery pertama
                ->select('office_css.*', 'office_technicians.*')
                ->leftJoin(DB::raw('(' . $subQueryTechnicians->toSql() . ') as office_technicians'), function ($join) {
                    $join->on('office_css.cs_id', '=', 'office_technicians.cs_id');
                })
                ->mergeBindings($subQueryTechnicians)
                ->when(request()->search, function ($data) {
                    $data = $data->where('bankGroupId', 'like', '%' . request()->search . '%')
                        ->orWhere('accountNum', 'like', '%' . request()->search . '%')
                        ->orWhere('name', 'like', '%' . request()->search . '%');
                })->latest()->paginate(10);
        } else {
                        $subQueryA = DB::table('office_cs')
                ->select(
                    'office_cs.*',
                    'office_cs.id as cs_id',
                    'customer.accountNum as customer_code',
                    'customer.name as customer_name',
                    'customer.email',
                    'customer.phone',
                    DB::raw('(select name from users where id = office_cs.saved_id) as cs_name')
                )
                ->leftJoin('customer', 'office_cs.customer_id', '=', 'customer.id')
                ->groupBy(
                    'office_cs.id',
                    'office_cs_item.cs_id',
                    'office_cs_item.id',
                    'office_cs_item.is_active',
                    'office_cs_item.item_id',
                    'office_cs_item.type',
                    'office_cs_item.approval_cust',
                    'office_cs_item.warranty',
                    'office_cs_item.incoming_source',
                    'office_cs_item.req_part',
                    'office_cs_item.req_part_to_whs',
                    'office_customer_item.item_code',
                    'office_customer_item.serial_number',
                    'customer.accountNum',
                    'customer.name',
                    'customer.phone',
                    'users.name'
                );

            // Subquery kedua (office_technicians)
            $subQueryTechnicians = DB::table('office_technician')
                ->join('office_cs_item', 'office_cs_item.technician_id', '=', 'office_technician.id')
                ->join('office_customer_item', 'office_cs_item.item_id', '=', 'office_customer_item.id')
                ->select(
                    'office_cs_item.cs_id',
                    DB::raw('date(office_cs_item.created_at) as date_input'),
                    DB::raw('date(office_cs_item.time_process) as time_process'),
                    DB::raw('date(office_cs_item.time_done) as time_done'),
                    DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                    DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                    DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty')
                )
                ->groupBy('office_cs_item.cs_id',
                    'office_cs_item.created_at',
                    'office_cs_item.time_process',
                    'office_cs_item.time_done');


            $data = DB::table(DB::raw('(' . $subQueryA->toSql() . ') as office_css'))
                ->mergeBindings($subQueryA) // Menggabungkan binding dari subquery pertama
                ->select('office_css.*', 'office_technicians.*')
                ->leftJoin(DB::raw('(' . $subQueryTechnicians->toSql() . ') as office_technicians'), function ($join) {
                    $join->on('office_css.cs_id', '=', 'office_technicians.cs_id');
                })
                ->mergeBindings($subQueryTechnicians)
                ->when(request()->search, function ($data) {
                    $data = $data->where('bankGroupId', 'like', '%' . request()->search . '%')
                        ->orWhere('accountNum', 'like', '%' . request()->search . '%')
                        ->orWhere('name', 'like', '%' . request()->search . '%');
                })->latest()->paginate(10);
        }

        $data->appends(['search' => request()->search]);

        return new OfficeCsResource(true, 'List data Office CS', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_create' => 'required',
            'customer_id'  => 'required',
            'warranty'  => 'required',
            'cost'  => 'required',
            'incoming_source'  => 'required',
            'service_type'  => 'required',
            'technician_id'  => 'required',
            'problem'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $requests = $request->all();
        $user = auth()->guard('api')->user();
        $ppn = Item_sales_taxs::where('tipe', 1)->where('ppn_active', 1)->first();
        $cust = Customers::whereId($request->customer_id)->first();
        $dateString = $request->date_create;
        $cleanDateString = preg_replace('/\s*\(.*\)$/', '', $dateString);
        $date = Carbon::parse($cleanDateString);

        $cs = new Office_css();
		$cs->code = Office_css::getNextCode($user->location_id);
        $cs->date_create = $date->format('Y-m-d H:i:s');
        $cs->customer_id = $request->customer_id;
        $cs->company_id = $user->company_id;
        $cs->warehouse_id = $user->warehouse_id;
        $cs->location_id = $user->location_id;
        $cs->saved_id = $user->id;
        $cs->is_ppn = 1;
        $cs->ppn_percen = $ppn->percen;
        $cs->is_active = $request->is_active;
        $cs->is_berikat = $cust->is_berikat;

        if($request->is_active == 1){
            $cs->posting_date = date("Y-m-d");
        }

        if(isset($requests['time_request'])){
				$cs->time_request   = $requests['time_request'];
			}
        $cs->save();

        if(isset($requests['item_id'])){
			$cust_item = Office_customer_items::find($requests['item_id']);

			if($cust_item->warranty == 1 && $requests['warranty'] == 0){
				$cust_item->warranty				= $requests['warranty'];
				$cust_item->history_date_warranty	= $cust_item->date_warranty;
				$cust_item->date_warranty			= NULL;
			}else{
				$cust_item->warranty				= $requests['warranty'];

				if(isset($requests['date_warranty'])){
					$cust_item->date_warranty 		= $requests['date_warranty'];
				}
			}

			if(isset($requests['serial_number'])){
				$cust_item->serial_number 	= $requests['serial_number'];
			}

			$cust_item->save();

		}else{
			$cust_item = new Office_customer_items;

			$cust_item->customer_id			= $requests['customer_id'];
			$cust_item->item_code			= $requests['item_code'];
			$cust_item->item_name			= $requests['item_name'];
			$cust_item->item_unit			= $requests['item_unit'];
			$cust_item->product_code		= $requests['product_code'];
			$cust_item->category_office		= $requests['category_office'];
			$cust_item->warranty			= $requests['warranty'];

			if(isset($requests['serial_number'])){
				$cust_item->serial_number 	= $requests['serial_number'];
			}

			if(isset($requests['date_warranty'])){
				$cust_item->date_warranty 	= $requests['date_warranty'];
			}

			$cust_item->save();
		}

        $cs_item = new Office_cs_items();
        $cs_item->cs_id = $cs->id;
        $cs_item->item_id = $cust_item->id;
        $cs_item->warranty = $request->warranty;
        $cs_item->cost = $request->cost;
        $cs_item->incoming_source = $request->incoming_source;
        $cs_item->service_type = $request->service_type;
        $cs_item->technician_id = $request->technician_id;
        $cs_item->problem = $request->problem;
        $cs_item->is_active = 0;

        if($request->warranty == 1){
            $cs_item->cost = 0;
        }

        $cs_item->counter = $request->counter ?? 0;
        $cs_item->note_cs = $request->note_cs ?? '';
        $cs_item->cost_onsite_id = $request->cost_onsite_id ?? '';
        $cs_item->cost_onsite = $request->cost_onsite ?? '';
        $cs_item->job_request = $request->job_request ?? '';

        $cs_item->save();



        // $items = is_string($request->office_cost_item) ? json_decode($request->office_cost_item, true) : $request->office_cost_item;
        // if (!is_array($items)) {
        //     Log::error('office_cost_item is not an array:', ['data' => $items]);
        //     return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
        // }

        // foreach ($items as $item) {
        //     if(!empty($item['id'])){
        //         $office_cost_item = Office_customer_items::whereId($item['id'])->first();
        //         $office_cost_item->cost_id = $office_cost->id;
        //         $office_cost_item->item_name = $item['name'] ?? null;
        //         $office_cost_item->item_code = $item['code'] ?? null;
        //         $office_cost_item->item_unit = $item['unit'] ?? null;
        //         $office_cost_item->product_code = $item['product_code'] ?? null;
        //         $office_cost_item->category_office = $item['category_office'] ?? null;
        //         $office_cost_item->cost = $office_cost->cost;
        //         $office_cost_item->cancel_cost = $office_cost->cancel_cost;
        //         $office_cost_item->save();
        //     }else{
        //         $office_cost_item = new Office_customer_items();
        //         $office_cost_item->cost_id = $office_cost->id;
        //         $office_cost_item->item_name = $item['name'] ?? null;
        //         $office_cost_item->item_code = $item['code'] ?? null;
        //         $office_cost_item->item_unit = $item['unit'] ?? null;
        //         $office_cost_item->product_code = $item['product_code'] ?? null;
        //         $office_cost_item->category_office = $item['category_office'] ?? null;
        //         $office_cost_item->cost = $office_cost->cost;
        //         $office_cost_item->cancel_cost = $office_cost->cancel_cost;
        //         $office_cost_item->save();
        //     }
        // }

        if($cs)
        {
            return new OfficeCsResource(true, 'Data Office CS Berhasil Disimpan!', $cs);
        }

        return new OfficeCsResource(false, 'Data Bank Account Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $cs = Office_css::whereId($id)->with('office_parts')->first();

        if($cs)
        {
            return new OfficeCsResource(true, 'Detail Data Office CS!', $cs);
        }

        return new OfficeCsResource(false, 'Detail Data Office CS Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'is_active'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cs = Office_css::whereId($id)->first();

        $user = auth()->guard('api')->user();

        $cs->saved_id = $user->id;
        $cs->is_active = $request->is_active;
        if($request->is_active == 1){
            $cs->posting_date = date("Y-m-d H:i:s");
        }
        $cs->time_request = $request->time_request ?? '';
        $cs->is_destroyed = $request->is_destroyed ?? 0;

        $cs->save();

        $cust_item = Office_customer_items::whereId($request->item_id)->first();
        if($cust_item->warranty == 1 && $request->warranty == 0){
            $cust_item->warranty = $request->warranty;
            $cust_item->history_date_warranty = $cust_item->date_warranty;
            $cust_item->date_warranty = NULL;
        }else{
            $cust_item->warranty = $request->warranty;
            $cust_item->date_warranty = $request->date_warranty ?? NULL;
        }

        $cust_item->serial_number = $request->serial_number ?? 0;

        $cust_item->save();

        $cs_item = Office_cs_items::where('cs_id', $id)->where('item_id', $request->item_id)->first();

        if($cs_item){
            $cs_item->warranty = $request->warranty;
            $cs_item->cost = $request->cost;
            $cs_item->incoming_source = $request->incoming_source;
            $cs_item->service_type = $request->service_type;
            $cs_item->technician_id = $request->technician_id;
            $cs_item->problem = $request->problem;
            $cs_item->is_active = $request->is_active;
            $cs_item->counter = $request->counter ?? 0;
            $cs_item->note_cs = $request->note_cs ?? '';
            $cs_item->cost_onsite_id = $request->cost_onsite_id ?? 0;
            $cs_item->cost_onsite = $request->cost_onsite ?? 0;
            $cs_item->job_request = $request->job_request ?? 0;

            if($request->is_active_item == 1){
                $cs_item->time_post = date("Y-m-d H:i:s");
            }
            $cs_item->save();
        }else{
            $del_cs_item = Office_cs_items::where('cs_id', $id)->delete();
            $cs_items = new Office_cs_items();

            $cs_items->cs_id = $cs->id;
            $cs_items->item_id = $cust_item->id;
            $cs_items->warranty = $request->warranty;
            $cs_items->cost = $request->cost;
            $cs_items->incoming_source = $request->incoming_source;
            $cs_items->service_type = $request->service_type;
            $cs_items->technician_id = $request->technician_id;
            $cs_items->problem = $request->problem;
            $cs_items->is_active = 0;
            $cs_items->counter = $request->counter ?? 0;
            $cs_items->note_cs = $request->note_cs ?? '';
            $cs_items->cost_onsite_id = $request->cost_onsite_id ?? 0;
            $cs_items->cost_onsite = $request->cost_onsite ?? 0;
            $cs_items->job_request = $request->job_request ?? 0;

            $cs_items->save();
        }

        $customer = Customers::whereId($cs->customer_id)->first();
        if($customer->is_not_dp == 0){
            $cs_for_wa = Office_css::join("location", "location.id", "=", "office_cs.location_id")
                ->join("customer", "customer.id", "=", "office_cs.customer_id")
                ->join("office_cs_item", "office_cs_item.cs_id", "=", "office_cs.id")
                ->join("office_customer_item", "office_customer_item.id", "=", "office_cs_item.item_id")
                ->join("users", "users.id", "=", "office_cs.saved_id")
                ->selectRaw('
                    location.name AS location_name,
                    office_cs.code AS nomor_service,
                    CASE
                        WHEN customer.uniq_name = "Company"
                        THEN customer.name
                        ELSE "Personal/Pribadi"
                    END as perusahaan,
                    customer.name AS name_customer,
                    office_customer_item.item_name AS type_unit,
                    office_customer_item.serial_number AS sn,
                    CASE
                        WHEN office_cs_item.warranty = 1
                        THEN "YA"
                        ELSE "TIDAK"
                    END as status_warranty,
                    SUBSTRING(office_cs_item.problem, 1, 40) AS problem_summary,
                    DATE_FORMAT(office_cs.date_create, "%d/%m/%Y") AS tanggal_terima,
                    users.name AS customer_service_handle
                ')
                ->where("office_cs.id", "=", $id)
                ->first();

            // Get WhatsApp Auth Token
            $qontak_auth = Auth_token_qontak_whatsapps::where("is_active", "=", 1)->first();

            // Prepare cURL request
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'to_number' => $customer->phone,
                    'to_name' => $customer->name,
                    'message_template_id' => 'f6250601-45d5-4a07-bc76-27854705971b',
                    'channel_integration_id' => 'd84787ce-41a7-4c5b-a992-e080f89008c9',
                    'language' => [
                        'code' => 'id'
                    ],
                    'parameters' => [
                        'body' => [
                            [
                                'key' => '1',
                                'value' => 'lokasi',
                                'value_text' => $cs_for_wa->location_name
                            ],
                            [
                                'key' => '2',
                                'value' => 'no_service',
                                'value_text' => $cs_for_wa->nomor_service
                            ],
                            [
                                'key' => '3',
                                'value' => 'perusahaan',
                                'value_text' => $cs_for_wa->perusahaan
                            ],
                            [
                                'key' => '4',
                                'value' => 'customer',
                                'value_text' => $cs_for_wa->name_customer
                            ],
                            [
                                'key' => '5',
                                'value' => 'type_unit',
                                'value_text' => $cs_for_wa->type_unit
                            ],
                            [
                                'key' => '6',
                                'value' => 'sn',
                                'value_text' => $cs_for_wa->sn
                            ],
                            [
                                'key' => '7',
                                'value' => 'warranty',
                                'value_text' => $cs_for_wa->status_warranty
                            ],
                            [
                                'key' => '8',
                                'value' => 'desc',
                                'value_text' => $cs_for_wa->problem_summary
                            ],
                            [
                                'key' => '9',
                                'value' => 'tgl_terima',
                                'value_text' => $cs_for_wa->tanggal_terima
                            ],
                            [
                                'key' => '10',
                                'value' => 'cs',
                                'value_text' => $cs_for_wa->customer_service_handle
                            ],
                        ]
                    ]
                ]),
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$qontak_auth->authorization_type} {$qontak_auth->authorization_value}",
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return response()->json(['error' => "cURL Error #: $err"], 500);
            } else {
                return response()->json(['success' => json_decode($response)], 200);
            }

        }

        if($cs)
        {
            return new OfficeCsResource(true, 'Data Bank Account Berhasil Diupdate!', $cs);
        }

        return new OfficeCsResource(false, 'Data Bank Account Gagal Diupdate!', null);
    }

    public function destroy(Office_bank_accounts $bankaccount)
    {
        if($bankaccount->delete())
        {
            return new OfficeCsResource(true, 'Data Bank Account Berhasil Dihapus!', null);
        }

        return new OfficeCsResource(false, 'Data Bank Account Gagal Dihapus!', null);
    }

    public function all()
    {
        $Office_bank_accounts = Office_bank_accounts::latest()->get();

        return new OfficeCsResource(true, 'List Data Bank Account', $Office_bank_accounts);
    }

    public function filters($status)
    {
        $user = auth()->guard('api')->user();

        if($status == 100){
            if($user->office_team == 1){
                $data = DB::table('office_cs')
                    ->select(
                        'office_cs.*',
                        DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                        'office_cs_item.cs_id',
                        'office_cs_item.id as oci_id',
                        'office_cs_item.is_active as is_active_ts',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        DB::raw('date(office_cs_item.created_at) as date_input'),
                        DB::raw('date(office_cs_item.time_process) as time_process'),
                        DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                        DB::raw('date(office_cs_item.time_done) as time_done'),
                        DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                        DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                        'office_technician.user_id as user_ts',
                        DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                        DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                        'customer.accountNum as customer_code',
                        'customer.name as customer_name',
                        'customer.phone as customer_phone',
                        'users.name as cs_name',
                        DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                    )
                    ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                    ->join('office_customer_item', function ($join) {
                        $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                            ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                    })
                    ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                    ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                    ->join('users', 'users.id', '=', 'office_cs.saved_id')
                    ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
                    ->groupBy(
                        'office_cs.id',
                        'office_cs_item.cs_id',
                        'office_cs_item.id',
                        'office_cs_item.is_active',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        'customer.accountNum',
                        'customer.name',
                        'customer.phone',
                        'users.name'
                    )
                    ->when(request()->search, function ($data) {
                        $data = $data->where('cust_item', 'like', '%' . request()->search . '%')
                            ->orWhere('serial_number', 'like', '%' . request()->search . '%')
                            ->orWhere('location_name', 'like', '%' . request()->search . '%')
                            ->orWhere('code', 'like', '%' . request()->search . '%');
                    })->latest()->paginate(10);
            } else if($user->office_team == 2){
                $data = DB::table('office_cs')
                    ->select(
                        'office_cs.*',
                        DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                        'office_cs_item.cs_id',
                        'office_cs_item.id as oci_id',
                        'office_cs_item.is_active as is_active_ts',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        DB::raw('date(office_cs_item.created_at) as date_input'),
                        DB::raw('date(office_cs_item.time_process) as time_process'),
                        DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                        DB::raw('date(office_cs_item.time_done) as time_done'),
                        DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                        DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                        'office_technician.user_id as user_ts',
                        DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                        DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                        DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                    )
                    ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                    ->join('office_customer_item', function ($join) {
                        $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                            ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                    })
                    ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                    ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                    ->join('users', 'users.id', '=', 'office_cs.saved_id')
                    ->where('office_cs.location_id', '=', $user->location_id)
                    ->groupBy(
                        'office_cs.id',
                        'office_cs_item.cs_id',
                        'office_cs_item.id',
                        'office_cs_item.is_active',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                    )
                    ->when(request()->search, function ($data) {
                        $data = $data->where('cust_item', 'like', '%' . request()->search . '%')
                            ->orWhere('serial_number', 'like', '%' . request()->search . '%')
                            ->orWhere('location_name', 'like', '%' . request()->search . '%')
                            ->orWhere('code', 'like', '%' . request()->search . '%');
                    })->latest()->paginate(10);
            } else {
                $data = DB::table('office_cs')
                    ->select(
                        'office_cs.*',
                        DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                        'office_cs_item.cs_id',
                        'office_cs_item.id as oci_id',
                        'office_cs_item.is_active as is_active_ts',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        DB::raw('date(office_cs_item.created_at) as date_input'),
                        DB::raw('date(office_cs_item.time_process) as time_process'),
                        DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                        DB::raw('date(office_cs_item.time_done) as time_done'),
                        DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                        DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                        'office_technician.user_id as user_ts',
                        DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                        DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                        'customer.accountNum as customer_code',
                        'customer.name as customer_name',
                        'customer.phone as customer_phone',
                        'users.name as cs_name',
                        DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                    )
                    ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                    ->join('office_customer_item', function ($join) {
                        $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                            ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                    })
                    ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                    ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                    ->join('users', 'users.id', '=', 'office_cs.saved_id')
                    ->groupBy(
                        'office_cs.id',
                        'office_cs_item.cs_id',
                        'office_cs_item.id',
                        'office_cs_item.is_active',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        'customer.accountNum',
                        'customer.name',
                        'customer.phone',
                        'users.name'
                    )
                    ->when(request()->search, function ($data) {
                        $data = $data->where('cust_item', 'like', '%' . request()->search . '%')
                            ->orWhere('serial_number', 'like', '%' . request()->search . '%')
                            ->orWhere('location_name', 'like', '%' . request()->search . '%')
                            ->orWhere('code', 'like', '%' . request()->search . '%');
                    })->latest()->paginate(10);
            }
        } else {
            $search = match ($status) {
                4 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 2 AND office_cs_item.req_part = 1
                      AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id)>0,
                      (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id),0)) >
                      (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                      (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))
                      AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) > 0)",
                12 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 0 AND office_cs_item.req_part_to_whs = 0
                       AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id)>0,
                       (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id),0)) >
                       (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                       (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))",
                13 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 2 AND office_cs_item.req_part_to_whs = 0
                       AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id)>0,
                       (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id),0)) >
                       (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                       (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))
                       AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 group by office_part.cs_id) is null)",
                6 => "office_cs.is_active = 4 AND office_cs_item.req_part = 1
                      AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id) =
                      (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id))",
                7 => "(office_cs.is_active = 7 AND office_cs.send_bad_part = 0)",
                15 => "(office_cs.is_active = 7 AND office_cs.send_bad_part = 1)",
                8 => "(office_cs.is_active = 8 AND office_cs.is_return_part = 2 AND office_cs.is_salesinvoice is null)",
                16 => "(office_cs.is_active = 8 AND office_cs.is_return_part = 1 AND office_cs.is_salesinvoice is null)",
                default => "office_cs.is_active = $status",
            };

            if($user->office_team == 1){
                // Query utama
            $data = DB::table('office_cs')
                ->select(
                    'office_cs.*',
                    DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                    'office_cs_item.cs_id',
                    'office_cs_item.id as oci_id',
                    'office_cs_item.is_active as is_active_ts',
                    'office_cs_item.item_id',
                    'office_cs_item.type',
                    'office_cs_item.approval_cust',
                    'office_cs_item.warranty',
                    'office_cs_item.incoming_source',
                    DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                    'office_cs_item.req_part',
                    'office_cs_item.req_part_to_whs',
                    DB::raw('date(office_cs_item.created_at) as date_input'),
                    DB::raw('date(office_cs_item.time_process) as time_process'),
                    DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                    DB::raw('date(office_cs_item.time_done) as time_done'),
                    DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                    DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                    'office_technician.user_id as user_ts',
                    DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                    'office_customer_item.item_code',
                    'office_customer_item.serial_number',
                    DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                    DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                    'customer.accountNum as customer_code',
                    'customer.name as customer_name',
                    'users.name as cs_name',
                    DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                    DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                    DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                    DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                )
                ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                ->join('office_customer_item', function ($join) {
                    $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                         ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                })
                ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                ->join('users', 'users.id', '=', 'office_cs.saved_id')
                ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
                ->whereRaw($search) // Gunakan kondisi pencarian
                ->groupBy(
                    'office_cs.id',
                    'office_cs_item.cs_id',
                    'office_cs_item.id',
                    'office_cs_item.is_active',
                    'office_cs_item.item_id',
                    'office_cs_item.type',
                    'office_cs_item.approval_cust',
                    'office_cs_item.warranty',
                    'office_cs_item.incoming_source',
                    'office_cs_item.req_part',
                    'office_cs_item.req_part_to_whs',
                    'office_customer_item.item_code',
                    'office_customer_item.serial_number',
                    'customer.accountNum',
                    'customer.name',
                    'customer.phone',
                    'users.name'
                )
                ->when(request()->search, function ($query) {
                    $query->where('cust_item', 'like', '%' . request()->search . '%')
                          ->orWhere('serial_number', 'like', '%' . request()->search . '%')
                          ->orWhere('location_name', 'like', '%' . request()->search . '%')
                          ->orWhere('code', 'like', '%' . request()->search . '%');
                })
                ->latest()
                ->paginate(10);
            } else if($user->office_team == 2){
                // Query utama
                $data = DB::table('office_cs')
                    ->select(
                        'office_cs.*',
                        DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                        'office_cs_item.cs_id',
                        'office_cs_item.id as oci_id',
                        'office_cs_item.is_active as is_active_ts',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        DB::raw('date(office_cs_item.created_at) as date_input'),
                        DB::raw('date(office_cs_item.time_process) as time_process'),
                        DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                        DB::raw('date(office_cs_item.time_done) as time_done'),
                        DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                        DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                        'office_technician.user_id as user_ts',
                        DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                        DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                        DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                    )
                    ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                    ->join('office_customer_item', function ($join) {
                        $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                            ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                    })
                    ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                    ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                    ->join('users', 'users.id', '=', 'office_cs.saved_id')
                    ->where('office_cs.location_id', '=', $user->location_id)
                    ->whereRaw($search) // Gunakan kondisi pencarian
                    ->groupBy(
                        'office_cs.id',
                        'office_cs_item.cs_id',
                        'office_cs_item.id',
                        'office_cs_item.is_active',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                    )
                    ->when(request()->search, function ($query) {
                        $query->where('cust_item', 'like', '%' . request()->search . '%')
                            ->orWhere('serial_number', 'like', '%' . request()->search . '%')
                            ->orWhere('location_name', 'like', '%' . request()->search . '%')
                            ->orWhere('code', 'like', '%' . request()->search . '%');
                    })
                    ->latest()
                    ->paginate(10);
            } else {
                // Query utama
            $data = DB::table('office_cs')
                ->select(
                    'office_cs.*',
                    DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                    'office_cs_item.cs_id',
                    'office_cs_item.id as oci_id',
                    'office_cs_item.is_active as is_active_ts',
                    'office_cs_item.item_id',
                    'office_cs_item.type',
                    'office_cs_item.approval_cust',
                    'office_cs_item.warranty',
                    'office_cs_item.incoming_source',
                    DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                    'office_cs_item.req_part',
                    'office_cs_item.req_part_to_whs',
                    DB::raw('date(office_cs_item.created_at) as date_input'),
                    DB::raw('date(office_cs_item.time_process) as time_process'),
                    DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                    DB::raw('date(office_cs_item.time_done) as time_done'),
                    DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                    DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                    'office_technician.user_id as user_ts',
                    DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                    'office_customer_item.item_code',
                    'office_customer_item.serial_number',
                    DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                    DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                    'customer.accountNum as customer_code',
                    'customer.name as customer_name',
                    'users.name as cs_name',
                    DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                    DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                    DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                    DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                )
                ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                ->join('office_customer_item', function ($join) {
                    $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                         ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                })
                ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                ->join('users', 'users.id', '=', 'office_cs.saved_id')
                ->whereRaw($search) // Gunakan kondisi pencarian
                ->groupBy(
                    'office_cs.id',
                    'office_cs_item.cs_id',
                    'office_cs_item.id',
                    'office_cs_item.is_active',
                    'office_cs_item.item_id',
                    'office_cs_item.type',
                    'office_cs_item.approval_cust',
                    'office_cs_item.warranty',
                    'office_cs_item.incoming_source',
                    'office_cs_item.req_part',
                    'office_cs_item.req_part_to_whs',
                    'office_customer_item.item_code',
                    'office_customer_item.serial_number',
                    'customer.accountNum',
                    'customer.name',
                    'users.name'
                )
                ->when(request()->search, function ($query) {
                    $query->where('cust_item', 'like', '%' . request()->search . '%')
                          ->orWhere('serial_number', 'like', '%' . request()->search . '%')
                          ->orWhere('location_name', 'like', '%' . request()->search . '%')
                          ->orWhere('code', 'like', '%' . request()->search . '%');
                })
                ->latest()
                ->paginate(10);
            }
        }

        $data->appends(['search' => request()->search]);

        return new OfficeCsResource(true, "List data CS", $data);
    }

    public function filterWithSource($status, $inc_source)
    {
        $user = auth()->guard('api')->user();

        if($inc_source == 100){
            if($status == 100){
                if($user->office_team == 1){
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'customer.phone as customer_phone',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()->paginate(10);
                } else if($user->office_team == 2){
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.location_id', '=', $user->location_id)
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()->paginate(10);
                } else {
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'customer.phone as customer_phone',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()->paginate(10);
                }
            } else {
                $search = match ($status) {
                    4 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 2 AND office_cs_item.req_part = 1
                          AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id)>0,
                          (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id),0)) >
                          (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                          (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))
                          AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) > 0)",
                    12 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 0 AND office_cs_item.req_part_to_whs = 0
                           AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id),0)) >
                           (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))",
                    13 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 2 AND office_cs_item.req_part_to_whs = 0
                           AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id),0)) >
                           (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))
                           AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 group by office_part.cs_id) is null)",
                    6 => "office_cs.is_active = 4 AND office_cs_item.req_part = 1
                          AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id) =
                          (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id))",
                    7 => "(office_cs.is_active = 7 AND office_cs.send_bad_part = 0)",
                    15 => "(office_cs.is_active = 7 AND office_cs.send_bad_part = 1)",
                    8 => "(office_cs.is_active = 8 AND office_cs.is_return_part = 2 AND office_cs.is_salesinvoice is null)",
                    16 => "(office_cs.is_active = 8 AND office_cs.is_return_part = 1 AND office_cs.is_salesinvoice is null)",
                    default => "office_cs.is_active = $status",
                };

                if($user->office_team == 1){
                    // Query utama
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
                        ->whereRaw($search) // Gunakan kondisi pencarian
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()
                        ->paginate(10);
                } else if($user->office_team == 2){
                    // Query utama
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.location_id', '=', $user->location_id)
                        ->whereRaw($search) // Gunakan kondisi pencarian
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()
                        ->paginate(10);
                } else {
                    // Query utama
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->whereRaw($search) // Gunakan kondisi pencarian
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()
                        ->paginate(10);
                }
            }
        } else {
            if($status == 100){
                if($user->office_team == 1){
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'customer.phone as customer_phone',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
						->where('office_cs_item.incoming_source','=',$inc_source)
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()->paginate(10);
                } else if($user->office_team == 2){
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.location_id', '=', $user->location_id)
						->where('office_cs_item.incoming_source','=',$inc_source)
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()->paginate(10);
                } else {
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'customer.phone as customer_phone',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
						->where('office_cs_item.incoming_source','=',$inc_source)
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()->paginate(10);
                }
            } else {
                $search = match ($status) {
                    4 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 2 AND office_cs_item.req_part = 1
                          AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id)>0,
                          (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id),0)) >
                          (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                          (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))
                          AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) > 0)",
                    12 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 0 AND office_cs_item.req_part_to_whs = 0
                           AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id),0)) >
                           (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))",
                    13 => "office_cs.is_active = 4 AND office_cs_item.approval_cust = 2 AND office_cs_item.req_part_to_whs = 0
                           AND ((if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id),0)) >
                           (if((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id)>0,
                           (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id),0)))
                           AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 group by office_part.cs_id) is null)",
                    6 => "office_cs.is_active = 4 AND office_cs_item.req_part = 1
                          AND ((select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 group by office_part.cs_id) =
                          (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id AND office_part.is_active < 9 AND office_part.iptn_out_ts_id > 0 group by office_part.cs_id))",
                    7 => "(office_cs.is_active = 7 AND office_cs.send_bad_part = 0)",
                    15 => "(office_cs.is_active = 7 AND office_cs.send_bad_part = 1)",
                    8 => "(office_cs.is_active = 8 AND office_cs.is_return_part = 2 AND office_cs.is_salesinvoice is null)",
                    16 => "(office_cs.is_active = 8 AND office_cs.is_return_part = 1 AND office_cs.is_salesinvoice is null)",
                    default => "office_cs.is_active = $status",
                };

                if($user->office_team == 1){
                    // Query utama
                $data = DB::table('office_cs')
                    ->select(
                        'office_cs.*',
                        DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                        'office_cs_item.cs_id',
                        'office_cs_item.id as oci_id',
                        'office_cs_item.is_active as is_active_ts',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        DB::raw('date(office_cs_item.created_at) as date_input'),
                        DB::raw('date(office_cs_item.time_process) as time_process'),
                        DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                        DB::raw('date(office_cs_item.time_done) as time_done'),
                        DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                        DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                        'office_technician.user_id as user_ts',
                        DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                        DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                        'customer.accountNum as customer_code',
                        'customer.name as customer_name',
                        'users.name as cs_name',
                        DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                        DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                    )
                    ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                    ->join('office_customer_item', function ($join) {
                        $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                             ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                    })
                    ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                    ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                    ->join('users', 'users.id', '=', 'office_cs.saved_id')
                    ->where('office_cs.warehouse_id', '=', $user->warehouse_id)
					->where('office_cs_item.incoming_source','=',$inc_source)
                    ->whereRaw($search) // Gunakan kondisi pencarian
                    ->groupBy(
                        'office_cs.id',
                        'office_cs_item.cs_id',
                        'office_cs_item.id',
                        'office_cs_item.is_active',
                        'office_cs_item.item_id',
                        'office_cs_item.type',
                        'office_cs_item.approval_cust',
                        'office_cs_item.warranty',
                        'office_cs_item.incoming_source',
                        'office_cs_item.req_part',
                        'office_cs_item.req_part_to_whs',
                        'office_customer_item.item_code',
                        'office_customer_item.serial_number',
                        'customer.accountNum',
                        'customer.name',
                        'customer.phone',
                        'users.name'
                    )
                    ->when(request()->search, function ($query) {
                        $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                            ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                            ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                            ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                    })
                    ->latest()
                    ->paginate(10);
                } else if($user->office_team == 2){
                    // Query utama
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs.location_id', '=', $user->location_id)
						->where('office_cs_item.incoming_source','=',$inc_source)
                        ->whereRaw($search) // Gunakan kondisi pencarian
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()
                        ->paginate(10);
                } else {
                    // Query utama
                    $data = DB::table('office_cs')
                        ->select(
                            'office_cs.*',
                            DB::raw('if(office_cs.paid_time > 0, DATEDIFF(date(office_cs.paid_time), office_cs.date_create), DATEDIFF(NOW(), office_cs.date_create)) as tat_rma_unique'),
                            'office_cs_item.cs_id',
                            'office_cs_item.id as oci_id',
                            'office_cs_item.is_active as is_active_ts',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            DB::raw('(case when office_cs_item.incoming_source = 1 then "WALK-IN" when office_cs_item.incoming_source = 2 then "PICK UP" when office_cs_item.incoming_source = 3 then "ON-SITE" else "" end) as incomingsource'),
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            DB::raw('date(office_cs_item.created_at) as date_input'),
                            DB::raw('date(office_cs_item.time_process) as time_process'),
                            DB::raw('date(office_cs_item.time_done_ts) as time_done_ts'),
                            DB::raw('date(office_cs_item.time_done) as time_done'),
                            DB::raw('(select name from users where id=office_cs_item.user_process limit 1) as user_process'),
                            DB::raw('(select name from users where id=office_cs_item.user_done limit 1) as user_done'),
                            'office_technician.user_id as user_ts',
                            DB::raw('GROUP_CONCAT(DISTINCT office_technician.name) as technician_name'),
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            DB::raw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) as cust_item'),
                            DB::raw('GROUP_CONCAT(DISTINCT (if(office_customer_item.warranty > 0, "Yes", "No"))) as detail_warranty'),
                            'customer.accountNum as customer_code',
                            'customer.name as customer_name',
                            'users.name as cs_name',
                            DB::raw('(select name from location where id=office_cs.location_id) as location_name'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active > 0 and office_part.is_active < 9 group by office_part.cs_id) as part_process'),
                            DB::raw('(select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done')
                        )
                        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                        ->join('office_customer_item', function ($join) {
                            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
                        })
                        ->join('office_technician', 'office_technician.id', '=', 'office_cs_item.technician_id')
                        ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
                        ->join('users', 'users.id', '=', 'office_cs.saved_id')
                        ->where('office_cs_item.incoming_source','=',$inc_source)
                        ->whereRaw($search) // Gunakan kondisi pencarian
                        ->groupBy(
                            'office_cs.id',
                            'office_cs_item.cs_id',
                            'office_cs_item.id',
                            'office_cs_item.is_active',
                            'office_cs_item.item_id',
                            'office_cs_item.type',
                            'office_cs_item.approval_cust',
                            'office_cs_item.warranty',
                            'office_cs_item.incoming_source',
                            'office_cs_item.req_part',
                            'office_cs_item.req_part_to_whs',
                            'office_customer_item.item_code',
                            'office_customer_item.serial_number',
                            'customer.accountNum',
                            'customer.name',
                            'customer.phone',
                            'users.name'
                        )
                        ->when(request()->search, function ($query) {
                            $query->havingRaw('GROUP_CONCAT(DISTINCT office_customer_item.item_name) LIKE ?', ['%'.request()->search.'%'])
                                ->orWhere('office_customer_item.serial_number', 'like', '%'.request()->search.'%')
                                ->orWhere(DB::raw('(SELECT name FROM location WHERE id = office_cs.location_id)'), 'like', '%'.request()->search.'%')
                                ->orWhere('office_cs.code', 'like', '%'.request()->search.'%');
                        })
                        ->latest()
                        ->paginate(10);
                }
            }
        }

        $data->appends(['search' => request()->search]);

        return new OfficeCsResource(true, 'List data cs', $data);
    }

    public function StatusTransferPart($id)
    {
        $data = DB::table('office_cs')
                ->select(
                    DB::raw('if(now() > DATE_ADD(iptn.date_of_transfer, INTERVAL 6 WEEK), 1, 0) as cek_status')
                )
                ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                ->join('iptn', function ($join) {
                    $join->on('iptn.cs_id', '=', 'office_cs.id')
                        ->on('iptn.warehouse_id_receipt', '=', 'office_cs.warehouse_id');
                })
                ->where('office_cs.id', '=', $id)
                ->where('office_cs.is_active', '=', 4)
                ->where('office_cs_item.is_active', '<', 2)
                ->where('office_cs_item.approval_cust', '=', 2)
                ->where('office_cs_item.req_part', '=', 1)
                ->first();

        if($data){
            return new OfficeCsResource(true, 'Data office cs ditemukan', $data);
        }

        return new OfficeCsResource(false, 'Data office cs tidak ditemukan', null);
    }

    public function statusPickup($id)
    {
        $data = DB::table('office_cs')
                ->select(
                    DB::raw('if(now() > DATE_ADD(date(office_cs_item.time_done), INTERVAL 3 MONTH), 1, 0 ) as cek_status')
                )
                ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
                ->where('office_cs.id', $id )
                ->where(function($query)
                {
                    $query->where('office_cs.is_active', 7)
                    ->orWhere('office_cs.is_active', 8);
                })
                ->first();

        if($data){
            return new OfficeCsResource(true, 'Data status pickup', $data);
        }

        return new OfficeCsResource(false, 'Data status pickup tidak ditemukan', null);
    }

    public function saveRMA(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rma_number'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $office_cs = Office_css::whereId($id)->first();

        if(!empty($request->rma_number)){
            $office_cs->rma_number = $request->rma_number;
            $office_cs->date_input_rma = date("Y-m-d H:i:s");

            $office_cs_item = Office_cs_items::where('cs_id', $id)->first();
            $office_cs_part = Office_parts::where('cs_id', $id)->first();
            // dd($office_cs_part);
            $office_cs_item->rma_number = $request->rma_number;
            $office_cs_part->rma_number = $request->rma_number;
            $office_cs_item->save();
            $office_cs_part->save();
        }

        if(!empty($request->rma_number)){
            $date = $request->date_finish_rma;
            $time = date("H:i:s");

            $office_cs->date_finish_rma = $date. ''. $time;
        }
        $office_cs->po_service = $request->po_service ?? '';

        $office_cs->save();

        if($office_cs){
            return new OfficeCsResource(true, 'Data RMA berhasil disimpan', $office_cs);
        }

        return new OfficeCsResource(false, 'Data RMA gagal disimpan', null);
    }

    public function detailCs($id)
    {
        $data = Office_css::selectRaw('
            office_cs.*,
            office_cs_item.cs_id,
            office_cs_item.id as oci_id,
            office_cs_item.is_active as is_active_item,
            office_cs_item.item_id,
            office_cs_item.type,
            office_cs_item.approval_cust,
            office_cs_item.warranty,
            office_cs_item.req_part,
            office_cs_item.req_part_to_whs,
            office_cs_item.req_part_done,
            office_cs_item.incoming_source,
            office_cs_item.service_type,
            office_cs_item.cost,
            office_cs_item.counter,
            office_cs_item.problem,
            office_cs_item.note_cs,
            office_cs_item.note_ts,
            office_cs_item.job_request,
            date(office_cs_item.created_at) as date_input,
            date(office_cs_item.time_process) as time_process,
            date(office_cs_item.time_done) as time_done,
            office_cs_item.technician_id,
            office_cs_item.cost_onsite_id,
            office_cs_item.cost_onsite,
            (select user_id from office_technician where id=office_cs_item.technician_id) as user_ts,
            (select name from office_technician where id=office_cs_item.technician_id) as technician_name,
            (select npwp from customer where id=office_customer_item.customer_id) as customer_npwp,
            (select phone from customer where id=office_customer_item.customer_id) as customer_phone,
            (select email from customer where id=office_customer_item.customer_id) as customer_email,
            (select is_not_dp from customer where id=office_cs.customer_id) as is_not_dp,
            (select is_berikat from customer where id=office_cs.customer_id) as is_berikat,
            office_customer_item.item_code,
            office_customer_item.product_code,
            office_customer_item.item_name,
            office_customer_item.item_unit,
            office_customer_item.category_office,
            office_customer_item.serial_number,
            office_customer_item.date_warranty,
            (select count(id) from office_cs_item where item_id=office_customer_item.id) as cek_cust_item_service,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id group by office_part.cs_id) as cek_part,
            if(
                (select count(office_part.status_stock) from office_part where office_part.cs_id=office_cs.id and office_part.status_stock is not null group by office_part.cs_id) > 0,
                (select count(office_part.status_stock) from office_part where office_part.cs_id=office_cs.id and office_part.status_stock is not null group by office_part.cs_id),
                0
            ) as count_status_stock,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.iptn_ts_id > 0 and office_part.is_active = 0 and office_part.qty_iptn_out = 0 and office_part.set_req = 0 group by office_part.cs_id) as cek_req_ts,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.iptn_cs_id > 0 and office_part.is_active < 1 group by office_part.cs_id) as cek_req_cs,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.iptn_out_cs_id > 0 and office_part.is_active < 3 group by office_part.cs_id) as cek_ipto_whs,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.iptn_in_cs_id > 0 and office_part.is_active < 4 group by office_part.cs_id) as cek_iptin_cs,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.iptn_out_ts_id > 0 and office_part.is_active < 5 group by office_part.cs_id) as cek_ipto_cs,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.qty_iptn_out > 0 and office_part.is_active < 5 group by office_part.cs_id) as cek_qty_out,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active = 9 group by office_part.cs_id) as cancel_count,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active in(5,9) group by office_part.cs_id) as cek_part_on_ts,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active in(8,9) group by office_part.cs_id) as cek_part_done,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_total,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 group by office_part.cs_id) as part_process,
            (select count(office_part.id) from office_part where office_part.cs_id=office_cs.id and office_part.is_active < 9 and office_part.iptn_out_ts_id > 0 group by office_part.cs_id) as part_done,
            (select name from location where id=office_cs.location_id) as location_name
        ')
        ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
        ->join('office_customer_item', function ($join) {
            $join->on('office_customer_item.id', '=', 'office_cs_item.item_id')
                ->on('office_customer_item.customer_id', '=', 'office_cs.customer_id');
        })
        ->where('office_cs.id', '=', $id)
        ->first();

        if ($data) {
            return new OfficeCsResource(true, 'Detail data Office CS ditemukan', $data);
        }

        return new OfficeCsResource(false, 'Detail data Office CS tidak ditemukan', null);
    }

    public function detailParts($id)
    {
        $user = auth()->guard('api')->user();
        $date_now = date("Y-m-d");
        $cs = Office_css::selectRaw('office_cs.*, (select warehouse_ts_id from office_part where cs_id=office_cs.id limit 1) as warehouse_ts_id')->whereId($id)->first();

        if($user->user_group_id == 1){
            $officeParts = Office_parts::select(DB::raw("
                office_part.*,
                ROUND(office_part.price, 0) as price,
                office_cs.is_active as active_cs,
                office_cs_item.approval_cust,
                office_cs_item.warranty,
                stock.StockAkhir as stock,
                ROUND(
                    CASE
                        WHEN (SELECT type_item_office FROM item WHERE id = office_part.part_id) = 2 THEN price_list_item.price
                        WHEN (SELECT type_item_office FROM item WHERE id = office_part.part_id) = 3 THEN consumable.price
                        ELSE 0
                    END, 0
                ) as pl_price
            "))
            ->join('office_cs', 'office_cs.id', '=', 'office_part.cs_id')
            ->join('office_cs_item', function ($join) {
                $join->on('office_cs_item.cs_id', '=', 'office_cs.id')
                    ->on('office_cs_item.cs_id', '=', 'office_part.cs_id');
            })
            ->join('office_customer_item', 'office_customer_item.id', '=', 'office_part.item_id')

            // LEFT JOIN Price List Item
            ->leftJoin(DB::raw("(
                SELECT * FROM (
                    SELECT office_price_list_item.item_code,
                        office_price_list_item.item_unit,
                        office_price_list_item.price
                    FROM office_price_list_item
                    JOIN office_price_list
                    ON office_price_list.id = office_price_list_item.price_list_id
                    WHERE office_price_list.is_active = 1
                    AND office_price_list.is_submitted = 4
                    AND ('{$date_now}' BETWEEN periode_from AND periode_to)
                    AND office_price_list.warehouse_id = {$cs->warehouse_ts_id}
                    ORDER BY office_price_list_item.updated_at DESC
                    LIMIT 1000000
                ) pli
                GROUP BY pli.item_code, pli.item_unit
            ) AS price_list_item"), function ($join) {
                $join->on('office_part.part_id', '=', 'price_list_item.item_code');
            })

            // LEFT JOIN Consumable Item
            ->leftJoin(DB::raw("(
                SELECT * FROM (
                    SELECT office_consumable_item.item_code,
                        office_consumable_item.item_unit,
                        office_consumable_item.price
                    FROM office_consumable_item
                    JOIN office_consumable
                    ON office_consumable.id = office_consumable_item.consumable_id
                    WHERE office_consumable.is_active = 1
                    AND office_consumable.is_submitted = 4
                    AND ('{$date_now}' BETWEEN periode_from AND periode_to)
                    ORDER BY office_consumable_item.updated_at DESC
                    LIMIT 1000000
                ) cons
                GROUP BY cons.item_code, cons.item_unit
            ) AS consumable"), function ($join) {
                $join->on('office_part.part_id', '=', 'consumable.item_code');
            })

            // LEFT JOIN Stock
            ->leftJoin(DB::raw("(
                SELECT b.*,
                    ROUND(SUM(b.quantity), 2) AS StockAkhir,
                    ROUND(SUM(b.total), 2) AS SaldoAkhir
                FROM (
                    SELECT inventory.item_code,
                        inventory.item_name,
                        inventory.item_unit,
                        ROUND(SUM(qty), 2) AS quantity,
                        ROUND(SUM(total_price), 2) AS total,
                        transaction.warehouse_id,
                        transaction.date_transaction AS Tanggal
                    FROM transaction
                    JOIN inventory
                    ON transaction.id = inventory.transaction_id
                    WHERE transaction.transaction_type NOT IN (14, 15)
                    AND transaction.company_id = {$user->company_id}
                    AND transaction.warehouse_id = {$cs->warehouse_id}
                    AND DATE(transaction.date_transaction) > '2017-08-31'
                    GROUP BY inventory.item_code
                ) AS b
                GROUP BY b.item_code
                ORDER BY b.item_code ASC
            ) AS stock"), function ($join) {
                $join->on('office_part.part_id', '=', 'stock.item_code')
                    ->on('office_part.warehouse_id', '=', 'stock.warehouse_id');
            })

            ->where('office_cs.id', '=', $id)
            ->get();
        } else {
            $officeParts = Office_parts::select(DB::raw("
                office_part.*,
                ROUND(office_part.price, 0) AS price,
                office_cs.is_active AS active_cs,
                office_cs_item.approval_cust,
                office_cs_item.warranty,
                stock.StockAkhir AS stock,
                ROUND(
                    CASE
                        WHEN (SELECT type_item_office FROM item WHERE id = office_part.part_id) = 2 THEN price_list_item.price
                        WHEN (SELECT type_item_office FROM item WHERE id = office_part.part_id) = 3 THEN consumable.price
                        ELSE 0
                    END, 0
                ) AS pl_price
            "))
            ->join('office_cs', 'office_cs.id', '=', 'office_part.cs_id')
            ->join('office_cs_item', function ($join) {
                $join->on('office_cs_item.cs_id', '=', 'office_cs.id')
                    ->on('office_cs_item.cs_id', '=', 'office_part.cs_id');
            })
            ->join('office_customer_item', 'office_customer_item.id', '=', 'office_part.item_id')

            // LEFT JOIN: Price List Item
            ->leftJoin(DB::raw("(
                SELECT * FROM (
                    SELECT
                        office_price_list_item.item_code,
                        office_price_list_item.item_unit,
                        office_price_list_item.price
                    FROM office_price_list_item
                    JOIN office_price_list
                    ON office_price_list.id = office_price_list_item.price_list_id
                    WHERE office_price_list.is_active = 1
                    AND office_price_list.is_submitted = 4
                    AND ('{$date_now}' BETWEEN periode_from AND periode_to)
                    AND office_price_list.warehouse_id = {$cs->warehouse_ts_id}
                    ORDER BY office_price_list_item.updated_at DESC
                    LIMIT 1000000
                ) pli
                GROUP BY pli.item_code, pli.item_unit
            ) AS price_list_item"), function ($join) {
                $join->on('office_part.part_id', '=', 'price_list_item.item_code');
            })

            // LEFT JOIN: Consumable
            ->leftJoin(DB::raw("(
                SELECT * FROM (
                    SELECT
                        office_consumable_item.item_code,
                        office_consumable_item.item_unit,
                        office_consumable_item.price
                    FROM office_consumable_item
                    JOIN office_consumable
                    ON office_consumable.id = office_consumable_item.consumable_id
                    WHERE office_consumable.is_active = 1
                    AND office_consumable.is_submitted = 4
                    AND ('{$date_now}' BETWEEN periode_from AND periode_to)
                    ORDER BY office_consumable_item.updated_at DESC
                    LIMIT 1000000
                ) cons
                GROUP BY cons.item_code, cons.item_unit
            ) AS consumable"), function ($join) {
                $join->on('office_part.part_id', '=', 'consumable.item_code');
            })

            // LEFT JOIN: Stock (pakai warehouse dari $user)
            ->leftJoin(DB::raw("(
                SELECT b.*,
                    ROUND(SUM(b.quantity), 2) AS StockAkhir,
                    ROUND(SUM(b.total), 2) AS SaldoAkhir
                FROM (
                    SELECT
                        inventory.item_code,
                        inventory.item_name,
                        inventory.item_unit,
                        ROUND(SUM(qty), 2) AS quantity,
                        ROUND(SUM(total_price), 2) AS total,
                        transaction.warehouse_id,
                        transaction.date_transaction AS Tanggal
                    FROM transaction
                    JOIN inventory
                    ON transaction.id = inventory.transaction_id
                    WHERE transaction.transaction_type NOT IN (14, 15)
                    AND transaction.company_id = {$user->company_id}
                    AND transaction.warehouse_id = {$user->warehouse_id}
                    AND DATE(transaction.date_transaction) > '2017-08-31'
                    GROUP BY inventory.item_code
                ) AS b
                GROUP BY b.item_code
                ORDER BY b.item_code ASC
            ) AS stock"), function ($join) {
                $join->on('office_part.part_id', '=', 'stock.item_code')
                    ->on('office_part.warehouse_id', '=', 'stock.warehouse_id');
            })

            ->where('office_cs.id', '=', $id)
            ->get();
        }

        if($officeParts){
            return new OfficeCsResource(true, 'Success', $officeParts);
        }

        return new OfficeCsResource(false, 'failed', null);
    }

    public function office_cs_item_types($id, $status, $cs_id, $type, $item_code)
    {
        $user = auth()->guard('api')->user();

        $date_now = date("Y-m-d");

        if($type == 'RESET'){
            $cs_item = Office_cs_items::whereId($id)->first();

            if($cs_item->warranty == 0){
                $cek_reset = Office_reset_items::select('office_reset_item.*')
                    ->join('office_reset', 'office_reset.id', '=', 'office_reset_item.reset_id')
                    ->where('office_reset_item.item_code', '=', $item_code)
                    ->where('office_reset.is_active', '=', 1)
                    ->where('office_reset.is_submitted', '=', 4)
                    ->where('office_reset.periode_from', '<=', $date_now)
                    ->where('office_reset.periode_to', '>=', $date_now)
                    ->first();

                if($cek_reset){
                    $cs_item->cost = $cek_reset->price;
                }
            }else{
                $cs_item->cost = 0;
            }

            $cs_item->is_active = $status;
            $cs_item->type = $type;

            if($status == 1){
                $cs_item->time_process = date("Y-m-d H:i:s");
                $cs_item->user_process = $user->id;
            }else if($status == 2){
                $cs_item->time_done = date("Y-m-d H:i:s");
                $cs_item->user_done = $user->id;
            }

            $cs_item->save();

            if($status == 1){
                $cs = Office_css::whereId($cs_id)->first();
                $cs->is_active = 2;
                $cs->save();
            }
        }else{
            $cs_item = Office_cs_items::whereId($id)->first();

            $cs_item->is_active = $status;
            $cs_item->type = $type;

            if($status == 1){
                $cs_item->time_process = date("Y-m-d H:i:s");
                $cs_item->user_process = $user->id;
            }else if($status == 2){
                $cs_item->time_done = date("Y-m-d H:i:s");
                $cs_item->user_done = $user->id;
            }

            $cs_item->save();

            if($status == 1){
                $cs = Office_css::whereId($cs_id)->first();
                $cs->is_active = 2;
                $cs->save();
            }
        }

        if($cs_item){
            return new OfficeCsResource(true, 'Success', $cs_item);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function workSelectParts()
    {
        $data = DB::table('office_price_list')
                ->select(
                    'office_price_list.*',
                    'stock.qty as stock',
                    'stock.warehouse_id',
                    'warehouse.name as warehouse_name',
                )
                ->leftJoin('warehouse', 'office_price_list.warehouse_id', '=', 'warehouse.id')
                ->leftJoin('stock', function($join){
                    $join->on('office_price_list.item_id','=','stock.item_code')
								  ->on( 'office_price_list.warehouse_id','=','stock.warehouse_id');
						})
                ->when(request()->search, function ($data) {
                    $data = $data->where('office_price_list.item_name', 'like', '%' . request()->search . '%')
                        ->orWhere('office_price_list.item_code', 'like', '%' . request()->search . '%');
                })->latest()->paginate(10);

        if($data){
            return new OfficeCsResource(true, 'Success', $data);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function saveQty(Request $request, $id, $dex)
    {
        $part = Office_parts::whereId($id)->first();

        if ($request->has('office_parts')) {
            $quotationItemRequests = $request->input('office_parts');

            // Ambil item berdasarkan indeks $dex
            $quotationItemRequest = (array) $quotationItemRequests[$dex];

            // Temukan Office_parts berdasarkan ID
            $officePart = Office_parts::find($id);

            if ($officePart) {
                // Perbarui nilai jika tersedia dalam request
                if (isset($quotationItemRequest['qty'])) {
                    $officePart->qty = $quotationItemRequest['qty'];
                }

                if (isset($quotationItemRequest['qty_iptn_in'])) {
                    $officePart->qty_iptn_in = $quotationItemRequest['qty_iptn_in'];
                }

                if (isset($quotationItemRequest['qty_iptn_out'])) {
                    $officePart->qty_iptn_out = $quotationItemRequest['qty_iptn_out'];
                }

                // Simpan perubahan
                $officePart->save();
            }
        }

        if($officePart){
            return new OfficeCsResource(true, 'Success', $officePart);
        }
        return new OfficeCsResource(false, 'Failed', null);
    }

    public function saveStock($id, $dex, Request $request)
    {
        $requests = $request->all();
        $user = auth()->guard('api')->user();
        $office_part = Office_parts::find($id);

        if(!$office_part){
            return new OfficeCsResource(false, 'Failed', null);
        }

        if(isset($requests['office_parts'])) {
            $quotaion_itemRequests = $requests['office_parts'];

            $quotaion_itemRequest = (array) $quotaion_itemRequests[$dex];

            $office_part1 = Office_parts::find($id);

            if($office_part1) {


                if(isset($quotaion_itemRequest['status_stock']))
                    $office_part1->status_stock  = $quotaion_itemRequest['status_stock'];

                $office_part1->save();
            }
        }

        if($office_part1){
            return new OfficeCsResource(true, 'Success', $office_part1);
        }
    }

    public function processParts($id, $type, Request $request)
    {
        $user = auth()->guard('api')->user();
        $user_location_id = $user->location_id;
        $user_Warehouse_id = $user->warehouse_id;
        $technician = Office_technicians::whereId($user->id)->first();
        if($technician){
            $technician_id = $technician->id;
        } else {
            $technician_id = null;
        }
        $requests = $request->all();
        $requests['office_parts'] = json_decode($requests['office_parts'], true);
        // dd($office_part);

        if ($type == 0) { // TS Request to CS
            $cs_item = Office_cs_items::where('cs_id', $id)->first();
            if ($cs_item) {
                $cs_item->req_part = 1;
                $cs_item->save();

                $cs = Office_css::find($id);

                // Create request iptn to CS
                $iptn = new Iptns();
                $iptn->no_ipt = Iptns::getNextCounterId();
                $iptn->location_id_receipt = $user_location_id;
                $iptn->warehouse_id_receipt = $user_Warehouse_id;
                $iptn->location_id = $cs->location_id;
                $iptn->warehouse_id = $cs->warehouse_id;
                $iptn->date_of_transfer = now()->format('Y-m-d');
                $iptn->user_id = $user->id;
                $iptn->campboss_id = $user->id;
                $iptn->description = $cs->code . ' (Request from TS for service)';
                $iptn->is_submitted = 2;
                $iptn->is_service = 1;
                $iptn->cs_id = $id;
                $iptn->save();

                if (isset($requests['office_parts'])) {
                    $quotation_itemRequests = $requests['office_parts'];
                    // dd($quotation_itemRequests);
                    foreach ($quotation_itemRequests as $quotation_itemRequest) {
                        // dd($quotation_itemRequest);
                        if (!empty($quotation_itemRequest['qty'])) {
                            if (!empty($quotation_itemRequest['id'])) {
                                $part = Office_parts::where('cs_id', $id)
                                    ->where('part_id', $quotation_itemRequest['item_code'])
                                    ->count();
                                if ($part == 0) {
                                    $office_part = new Office_parts;
                                    $office_part->cs_id = $id;
                                    $office_part->cs_item_id = $cs_item->id;
                                    $office_part->item_id = $cs_item->item_id;
                                    $office_part->product_code = $quotation_itemRequest['product_code'];
                                    $office_part->part_id = $quotation_itemRequest['item_code'];
                                    $office_part->part_name = $quotation_itemRequest['item_name'];
                                    $office_part->part_unit = $quotation_itemRequest['item_unit'];
                                    $office_part->qty = $quotation_itemRequest['qty'];
                                    $office_part->price = $quotation_itemRequest['price'];
                                    $office_part->stock = $quotation_itemRequest['stock'];
                                    $office_part->warehouse_id = $cs->warehouse_id;
                                    $whs = Warehouses::find($user->warehouse_id);
                                    $office_part->user_ts = $user->id;
                                    $office_part->warehouse_ts_id = $user->warehouse_id;
                                    $office_part->warehouse_name = $whs->name ?? '';
                                    $item = Items::where('code', $quotation_itemRequest['item_code'])->first();
                                    $office_part->category_office = $item->category_office ?? null;
                                    $office_part->type_item_office = $item->type_item_office ?? null;
                                    $office_part->is_active = 0;
                                    if (isset($quotation_itemRequest['note'])) {
                                        $office_part->note = $quotation_itemRequest['note'];
                                    }
                                    $office_part->save();
                                    // dd($office_part);

                                    // Save iptn item
                                    $iptn_item = new Iptn_items();
                                    $iptn_item->iptn_id = $iptn->id;
                                    $iptn_item->item_code = $quotation_itemRequest['item_code'];
                                    $iptn_item->item_name = $quotation_itemRequest['item_name'];
                                    $iptn_item->item_unit = $quotation_itemRequest['item_unit'];
                                    $iptn_item->qty = $quotation_itemRequest['qty'];
                                    $iptn_item->price = 0;
                                    $iptn_item->total_price_item = 0;
                                    $iptn_item->office_part_id = $office_part->id;
                                    $iptn_item->save();
                                    // dd($iptn_item);

                                    $office_part->iptn_ts_id = $iptn_item->id;
                                    $office_part->save();
                                    // dd($office_part);
                                }
                            }
                        }
                    }

                    $cek_iptn_item = Iptn_items::where('iptn_id', $iptn->id)->get();
                    if ($cek_iptn_item->count() <= 0) {
                        Iptns::where('id', $iptn->id)->delete();
                    }
                }

                $cek_part = Office_parts::selectRaw('count(if(type_item_office=3, 1, 0)) as tot_cons')
                    ->where('cs_id', $id)
                    ->first();
                // dd($cek_part);

                if ($cs->is_active < 3) {
                    if ($cs_item->warranty > 0) {
                        if ($cek_part->tot_cons > 0) {
                            $cs->is_active = 4;
                            $cs->save();

                            $cs_item->approval_cust = 2;
                            $cs_item->save();
                        } else {
                            $cs->is_active = 3;
                            $cs->save();
                        }
                    } else {
                        $cs->is_active = 3;
                        $cs->save();
                        // dd($cs);
                    }
                }
            }
            return new OfficeCsResource(true, 'Draft adjustment in warranty (spare part) success', [$iptn, $cs_item, $cs, $office_part, $iptn_item]);
        }
        if ($type == 1) { // CS Request to Warehouse
            $cs_item = Office_cs_items::where('cs_id', $id)->first();
            if ($cs_item) {
                $cs_item->req_part_to_whs = 1;
                $cs_item->date_req_part_whs = date("Y-m-d H:i:s");
                $cs_item->save();

                $cs = Office_css::find($id);
                if($cs->is_active < 4){
                    $cs->is_active = 4;
                    $cs->save();
                }

                $cust_item = Office_customer_items::whereId($cs_item->item_id)->first();

                if($cs_item->warranty == 0){
                    $sparePart = DB::table('office_part')
                                    ->join('item','item.code','=','office_part.part_id')
									->where('office_part.cs_id',$id)
									->where('office_part.qty_iptn_out',0)
									->where('item.type_item_office','<',3)
									->get();

                    if($sparePart->count() > 0){
                                        // Create request iptn to whs
                        $iptn = new Iptns();
                        $iptn->no_ipt = Iptns::getNextCounterId();
                        $iptn->location_id_receipt = $user_location_id;
                        $iptn->warehouse_id_receipt = $user_Warehouse_id;
                        $iptn->location_id = $cs->location_id;
                        $iptn->warehouse_id = $cs->warehouse_id;
                        $iptn->date_of_transfer = now()->format('Y-m-d');
                        $iptn->user_id = $user->id;
                        $iptn->campboss_id = $user->id;
                        $iptn->description = $cs->code . ' (Request from TS for service)';
                        $iptn->is_submitted = 2;
                        $iptn->is_service = 1;
                        $iptn->cs_id = $id;
                        $iptn->save();

                        $parts = DB::table('office_part')
                                    ->select('office_part.*')
                                    ->join('item','item.code','=','office_part.part_id')
                                    ->where('office_part.cs_id', '=', $id)
                                    ->where('office_part.iptn_ts_id','>', 0)
                                    ->where('office_part.iptn_cs_id','=', 0)
                                    ->where('office_part.qty_iptn_out','=', 0)
                                    ->where('office_part.is_active','<', 9)
                                    ->where('office_part.set_req','=', 0)
                                    ->where('item.type_item_office','<',3)
                                    ->get();
                        foreach ($parts as $quotation) {
                            $iptn_item = new Iptn_items();
                            $iptn_item->iptn_id = $iptn->id;
                            $iptn_item->item_code = $quotation->part_id;
                            $iptn_item->item_name = $quotation->part_name;
                            $iptn_item->item_unit = $quotation->part_unit;
                            $iptn_item->qty = $quotation->qty;
                            $iptn_item->price = 0;
                            $iptn_item->total_price_item = 0;
                            $iptn_item->office_part_id = $quotation->id;
                            $iptn_item->save();

                            $office_part = Office_parts::find($quotation->id);
                            if ($office_part) {
                                $office_part->iptn_cs_id = $iptn_item->id;
                                $office_part->is_active = 1; // Request to Warehouse
                                $office_part->save();
                            }
                        }
                    }

                                    // Consumable
                    $consumables = Office_parts::join('item', 'item.code', '=', 'office_part.part_id')
                                    ->where('office_part.cs_id', $id)
                                    ->where('office_part.qty_iptn_out', 0)
                                    ->where('item.type_item_office', 3)
                                    ->get(['office_part.*']);
                    if ($consumables->count() > 0 && !empty($requests['whs_cons_id'])) {
                        $iptn = new Iptns;
                        $iptn->no_ipt = Iptns::getNextCounterId();
                        $iptn->location_id_receipt = $user_location_id;
                        $iptn->warehouse_id_receipt = $user_Warehouse_id;
                        $iptn->location_id = $requests['loc_cons_id'];
                        $iptn->warehouse_id = $requests['whs_cons_id'];
                        $iptn->date_of_transfer = $requests['date_transfer'];
                        $iptn->user_id = $user->id;
                        $iptn->campboss_id = $user->id;
                        $iptn->description = $cs->code . ' (Request from CS for service ' . ($cust_item ? $cust_item->item_name : '') . ')';
                        $iptn->is_submitted = 2;
                        $iptn->is_service = 1;
                        $iptn->cs_id = $id;
                        $iptn->save();

                        $parts = Office_parts::join('item', 'item.code', '=', 'office_part.part_id')
                                    ->where('office_part.cs_id', $id)
                                    ->where('office_part.iptn_ts_id', '>', 0)
                                    ->where('office_part.iptn_cs_id', 0)
                                    ->where('office_part.qty_iptn_out', 0)
                                    ->where('office_part.is_active', '<', 9)
                                    ->where('office_part.set_req', 0)
                                    ->where('item.type_item_office', 3)
                                    ->get(['office_part.*']);

                        foreach ($parts as $quotation) {
                            $iptn_item = new Iptn_items();
                            $iptn_item->iptn_id = $iptn->id;
                            $iptn_item->item_code = $quotation->part_id;
                            $iptn_item->item_name = $quotation->part_name;
                            $iptn_item->item_unit = $quotation->part_unit;
                            $iptn_item->qty = $quotation->qty;
                            $iptn_item->price = 0;
                            $iptn_item->total_price_item = 0;
                            $iptn_item->office_part_id = $quotation->id;
                            $iptn_item->save();

                            $office_part = Office_parts::find($quotation->id);
                            if ($office_part) {
                                $office_part->iptn_cs_id = $iptn_item->id;
                                $office_part->is_active = 1;
                                $office_part->save();
                            }
                        }
                    }
                } else {
                                // Warranty active: only spare part
                    $spare_parts = Office_parts::join('item', 'item.code', '=', 'office_part.part_id')
                                    ->where('office_part.cs_id', $id)
                                    ->where('office_part.qty_iptn_out', 0)
                                    ->get(['office_part.*']);
                    if ($spare_parts->count() > 0) {
                        $iptn = new Iptns;
                        $iptn->no_ipt = Iptns::getNextCounterId();
                        $iptn->location_id_receipt = $user_location_id;
                        $iptn->warehouse_id_receipt = $user_Warehouse_id;
                        $iptn->location_id = $requests['location_id'];
                        $iptn->warehouse_id = $requests['warehouse_id'];
                        $iptn->date_of_transfer = $requests['date_transfer'];
                        $iptn->user_id = $user->id;
                        $iptn->campboss_id = $user->id;
                        $iptn->description = $cs->code . ' (Request from CS for service ' . ($cust_item ? $cust_item->item_name : '') . ')';
                        $iptn->is_submitted = 2;
                        $iptn->is_service = 1;
                        $iptn->cs_id = $id;
                        $iptn->save();

                        $parts = Office_parts::join('item', 'item.code', '=', 'office_part.part_id')
                                    ->where('office_part.cs_id', $id)
                                    ->where('office_part.iptn_ts_id', '>', 0)
                                    ->where('office_part.iptn_cs_id', 0)
                                    ->where('office_part.qty_iptn_out', 0)
                                    ->where('office_part.set_req', 0)
                                    ->where('office_part.is_active', '<', 9)
                                    ->get(['office_part.*']);

                        foreach ($parts as $quotation) {
                            $iptn_item = new Iptn_items();
                            $iptn_item->iptn_id = $iptn->id;
                            $iptn_item->item_code = $quotation->part_id;
                            $iptn_item->item_name = $quotation->part_name;
                            $iptn_item->item_unit = $quotation->part_unit;
                            $iptn_item->qty = $quotation->qty;
                            $iptn_item->price = 0;
                            $iptn_item->total_price_item = 0;
                            $iptn_item->office_part_id = $quotation->id;
                            $iptn_item->save();

                            $office_part = Office_parts::find($quotation->id);
                            if ($office_part) {
                                $office_part->iptn_cs_id = $iptn_item->id;
                                $office_part->is_active = 1;
                                $office_part->save();
                            }
                        }

                            // Draft adjustment in warranty (spare part)
                        $transaction = new Transaction_temps();
                        $transaction->transaction_type = 23;
                        $transaction->user_id = $user->id;
                        $transaction->location_id = $requests['location_id'];
                        $transaction->warehouse_id = $requests['warehouse_id'];
                        $transaction->company_id = $user->company_id;
                        $transaction->code = Transaction_temps::getNextCodeAdjustment();
                        $transaction->barcode = Transaction_temps::getNextCounterAdjustmentBarcodeId();
                        $transaction->type = 1;
                        $transaction->date_required = now()->format('Y-m-d');
                        $transaction->adjustment_type = 1;
                        $transaction->explanation = $cs->code;
                        $transaction->iptn_id = $iptn->id;
                        $transaction->is_service = 1;
                        $transaction->cs_id = $id;
                        $transaction->save();

                        $spareparts = Office_parts::join('item', 'item.code', '=', 'office_part.part_id')
                                        ->where('office_part.cs_id', $id)
                                        ->where('office_part.iptn_ts_id', '>', 0)
                                        ->where('office_part.iptn_cs_id', '>', 0)
                                        ->where('office_part.qty_iptn_out', 0)
                                        ->where('office_part.set_req', 0)
                                        ->where('office_part.is_active', '<', 9)
                                        ->get(['office_part.*']);

                        foreach ($spareparts as $quotations) {
                                $inventory = new Inventory_temps();
                                $inventory->transaction_id = $transaction->id;
                                $inventory->warehouse_id = $transaction->warehouse_id;
                                $inventory->company_id = $transaction->company_id;
                                $inventory->item_code = $quotations->part_id;
                                $inventory->item_name = $quotations->part_name;
                                $inventory->item_unit = $quotations->part_unit;
                                $inventory->item_group = substr($quotations->part_id, 0, 3);
                                $inventory->qty = $quotations->qty;
                                $inventory->price = 0;
                                $inventory->total_price = 0;
                                $inventory->office_part_id = $quotations->id;
                                $inventory->save();
                            }
                        }
                    }
                }

            return new OfficeCsResource(true, 'Draft adjustment in warranty (spare part) created successfully', $cs);
        }
        if($type == 2){
            $cs = Office_css::whereId($id)->first();
            if($cs->is_active < 4){
                $cs->is_active = 4;
                $cs->save();
            }

            $ipto = Transactions::where('transaction_type', 6)
                    ->where('is_service', 1)
                    ->where('cs_id', $id)
                    ->where('warehouse_receive_id', $user->warehouse_id)
                    ->where('is_iptn_in', null)
                    ->get();
            foreach ($ipto as $quotations) {
                $transaction = new Transactions();
                $transaction->transction_type = 7;
                $transaction->date_receive = date("Y-m-d");
                $transaction->date_use = date("Y-m-d");
                $transaction->user_id = $user->id;
                $transaction->location_id = $user_location_id;
                $transaction->warehouse_id = $user_Warehouse_id;
                $transaction->company_id			 = $user->company_id;
                $transaction->iptn_out_id	 		 = $quotations->id;
                $transaction->iptn_out_code	 		 = $quotations->code;
                $transaction->code     				 = Transactions::getNextCodeIPTIN();
                $transaction->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
                $transaction->explanation 			 = $cs->code.' (Receive Part from Warehouse)';
                $transaction->approval 				 = $user->name;
                $transaction->is_service			 = 1;
                $transaction->cs_id			 		 = $id;

                $transaction->save();

                $transaction->date_transaction = $transaction->created_at;

                $transaction->save();

                $parts = Office_parts::selectRaw('office_part.*, inventory.id as ipto_detail_id, -inventory.qty as qty_ipto, -inventory.price as price_ipto')
						->join('inventory','inventory.office_part_id','=','office_part.id')
						->where('office_part.cs_id', '=', $id)
						->where('office_part.qty_iptn_in','>', 0)
						->where('office_part.is_active','<', 9)
						->whereRaw('office_part.qty > office_part.qty_in_cs')
						->where('inventory.transaction_id',$transaction->iptn_out_id)
						->get();

                foreach($parts as $quotation){
                    $cek_part = Office_parts::selectRaw('office_part.*, sum(-inventory.qty) as tot_qty_ipto, sum(if(inventory.is_iptn_in>0, (-inventory.qty), 0)) as tot_qty_iptn_in')
							->join('inventory','inventory.office_part_id','=','office_part.id')
							->join('transaction','transaction.id','=','inventory.transaction_id')
							->where('office_part.cs_id', '=', $id)
							->where('office_part.part_id', '=', $quotation->part_id)
							->where('transaction.transaction_type',6)
							->where('transaction.warehouse_receive_id',$user->warehouse_id)
							->groupBy('office_part.cs_id')
							->first();

                    if($cek_part->qty >= $cek_part->tot_qty_ipto && ($cek_part->tot_qty_iptn_in==0 || $cek_part->tot_qty_iptn_in >= $cek_part->qty_in_cs) ){

                        $inventory = new Inventorys();
                        $inventory->transaction_id          = $transaction->id;
                        $inventory->company_id              = $user->company_id;
                        $inventory->product_code            = $quotation->product_code;
                        $inventory->item_code               = $quotation->part_id;
                        $inventory->item_name               = $quotation->part_name;
                        $inventory->item_unit               = $quotation->part_unit;
                        if($quotation->qty_iptn_in == $quotation->qty_ipto){
                            $inventory->qty                     = $quotation->qty_iptn_in;
                        }else{
                            $inventory->qty                     = $quotation->qty_ipto;
                        }
                        $inventory->price                   = $quotation->price_ipto;
                        $inventory->total_price             = $inventory->qty * $inventory->price;
                        $inventory->item_group              = substr($quotation->part_id,0,3);
                        $inventory->office_part_id			= $quotation->id;

                        $cekdebet = Inventory_postings::where('ItemRelation', substr($quotation->part_id,0,3))
                                            ->where('TransactionType', 7)
                                            ->where('InventAccountType', 1)
                                            ->first();

                        $cekcredit = Inventory_postings::where('ItemRelation', substr($quotation->part_id,0,3))
                                                    ->where('TransactionType', 7)
                                                    ->where('InventAccountType', 2)
                                                    ->first();

                        if($cekdebet){
                            $inventory->ledgerAccount = $cekdebet->LedgerAccountId;
                        }else{
                            $debet = Inventory_postings::where('TransactionType', 7)
                                                    ->where('InventAccountType', 1)
                                                    ->first();
                            $inventory->ledgerAccount = $debet->LedgerAccountId;
                        }

                        if($cekcredit){
                            $inventory->offsetAccount = $cekcredit->LedgerAccountId;
                        }else{
                            $credit = Inventory_postings::where('TransactionType', 7)
                                                    ->where('InventAccountType', 2)
                                                    ->first();
                            $inventory->offsetAccount = $credit->LedgerAccountId;
                        }

                        $inventory->save();

                        $cekstock = Stocks::where('item_code', $quotation->part_id)->where('warehouse_id', $user_Warehouse_id)->first();

                        if($cekstock){
                            $inventory->currentQty                  = $inventory->qty;
                            $inventory->currentValue                = $cekstock->price+($inventory->qty * $inventory->price);
                            $cekstock->item_code                    = $quotation->part_id;
                            $cekstock->item_name                    = $quotation->part_name;
                            $cekstock->item_unit                    = $quotation->part_unit;
                            $cekstock->location_id                  = $user_location_id;
                            $cekstock->warehouse_id                 = $user_Warehouse_id;
                            $cekstock->qty                          = $cekstock->qty+$inventory->qty;
                            $cekstock->price                        = $cekstock->price+($inventory->qty * $inventory->price);
                            if($cekstock->qty>0){
                                $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                            }else{
                                $cekstock->avg_price                    = 0;
                            }
                            $cekstock->save();
                        }else{
                            $cekstock = new Stocks();
                            $inventory->currentQty                  = $inventory->qty;
                            $inventory->currentValue                = ($inventory->qty * $inventory->price);
                            $cekstock->item_code                    = $quotation->part_id;
                            $cekstock->item_name                    = $quotation->part_name;
                            $cekstock->item_unit                    = $quotation->part_unit;
                            $cekstock->location_id                  = $user_location_id;
                            $cekstock->warehouse_id                 = $user_Warehouse_id;
                            $cekstock->qty                          = $inventory->qty;
                            $cekstock->price                        = ($inventory->qty * $inventory->price);
                            if($cekstock->qty>0){
                                $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                            }else{
                                $cekstock->avg_price                    = 0;
                            }
                            $cekstock->save();
                        }

                        $inventory->save();

                        $iptn_out = Inventorys::where('transaction_id',$transaction->iptn_out_id)
                                    ->where('item_code',$quotation->part_id)
                                    ->first();
                        if($iptn_out) {
                            $iptn_out->is_iptn_in = 1;
                            $iptn_out->save();
                        }

                        $office_part = Office_parts::find($quotation->id);
                        if($office_part){
                            $office_part->stock 			= $office_part->stock + $inventory->qty;
                            $office_part->qty_in_cs			= $office_part->qty_in_cs + $inventory->qty;
                            $office_part->iptn_in_cs_id		= $inventory->id;

                            if($office_part->qty_in_cs >= $office_part->qty){
                                $office_part->qty_in_cs 	= $office_part->qty;
                                $office_part->qty_iptn_in 	= $office_part->qty;
                                $office_part->is_active 	= 3; //Part Ready on CS
                            }

                            $office_part->save();
                        }
                    }

                    $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
                    if(!$delete_inventory){
                        $transaction_delete = Transactions::whereId($transaction->id)->first();
                        $transaction_delete->delete();
                    }

                    $cek_iptn_out_item = Inventorys::select(
                        DB::raw('count(id) as tot_item, sum(if(is_iptn_in > 0, 1, 0)) as tot_iptn_in')
                    )
                    ->where('transaction_id', $transaction->iptn_out_id)
                    ->first();

                    if ($cek_iptn_out_item->tot_item == $cek_iptn_out_item->tot_iptn_in) {
                        $cek_iptn_out = Transactions::find($transaction->iptn_out_id);
                        if ($cek_iptn_out) {
                            $cek_iptn_out->is_iptn_in = 1;
                            $cek_iptn_out->save();
                        }
                    }

                        // Bagian ledger transaction
                    $warehouse = Warehouses::find($transaction->warehouse_id);

                    $ledgerData = Transactions::select(
                        DB::raw('id, code')
                    )
                    ->where('id', $transaction->id);

                    $ledgerQuery = DB::table(
                        DB::raw('(' . $ledgerData->toSql() . ') items')
                    )
                    ->mergeBindings($ledgerData->getQuery())
                    ->select(
                        DB::raw('items.*, datas.*')
                    )
                    ->leftJoin(
                        DB::raw('(
                            SELECT c.* FROM (
                                SELECT ledgerAccount as LedgerAccountId, transaction_id, item_group, SUM(total_price) as total
                                FROM inventory
                                WHERE transaction_id = ' . $transaction->id . '
                                GROUP BY ledgerAccount
                                UNION
                            SELECT offsetAccount as LedgerAccountId, transaction_id, item_group, SUM(-total_price) as total
                                FROM inventory
                                WHERE transaction_id = ' . $transaction->id . '
                                GROUP BY offsetAccount
                            ) as c
                        ) datas'),
                        function ($join) {
                            $join->on('datas.transaction_id', '=', 'items.id');
                        }
                    )
                    ->where('datas.total', '!=', 0)
                    ->get();

                    foreach ($ledgerQuery as $quotaion1) {
                        $ledger = new Ledger_transactions();

                        $ledger->accountNum = $quotaion1->LedgerAccountId;
                        $ledger->transaction_date = $transaction->date_transaction;
                        $ledger->voucher = $transaction->code;
                        $ledger->amount = $quotaion1->total;
                        $ledger->currency = "IDR";
                        $ledger->rate = 1;
                        $ledger->total_basic = $quotaion1->total;
                        $ledger->dimention1 = $warehouse->dimention1;
                        $ledger->dimention2 = $warehouse->dimention2;
                        $ledger->dimention3 = $warehouse->dimention3;
                        $ledger->company_id = $transaction->company_id;
                        $ledger->user_id = $transaction->user_id;
                        $ledger->transaction_id = $transaction->id;

                        if ($transaction->explanation != null) {
                            $ledger->text = $transaction->explanation;
                        }

                        $ledger->save();
                    }
                }
            }
        }
        if($type == 3){
            $cs = Office_css::find($id);
			$cs->is_active = 4;
			$cs->is_part = 1;
			$cs->save();

            $iptn = Iptns::where('is_service',1)->where('cs_id',$id)->first();

            $transaction = new Transactions;

			$transaction->transaction_type       = 6;
			$transaction->user_id 				 = $user->id;
			$transaction->location_id 			 = $user_location_id;
			$transaction->warehouse_id			 = $user_Warehouse_id;
			$transaction->company_id			 = $user->company_id;
			$transaction->iptn_id 		 		 = $iptn->id;
			$transaction->no_ipt 		 		 = $iptn->no_ipt;
			$transaction->location_to_id 		 = $iptn->location_id_receipt;
			$transaction->warehouse_receive_id	 = $iptn->warehouse_id_receipt;
			$transaction->code     				 = Transactions::getNextCodeIPTO();
			$transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
			$transaction->explanation 			 = $cs->code.' (Send part from CS to TS)';
			$transaction->approval 				 = $user->name;
			$transaction->is_service			 = 1;
			$transaction->cs_id			 	 	 = $id;

			$transaction->save();

			$transaction->date_transaction = $transaction->created_at;

			$transaction->save();

			//----------------inventory--------------------
			$part = Iptn_items::selectRaw('iptn_item.id as iptn_item_id, iptn_item.iptn_id, iptn_item.office_part_id, office_part.*')
					->leftJoin('office_part', function ($join){
								$join->on('office_part.iptn_ts_id','=','iptn_item.id')
									 ->on('office_part.id','=','iptn_item.office_part_id')
									 ->on('office_part.part_id','=','iptn_item.item_code');
								})
					->where('office_part.cs_id','=',$id)
					->where('office_part.is_active','<', 9)
					//->where('office_part.iptn_in_cs_id','>', 0)
					//->where('office_part.iptn_out_ts_id','=', 0)
					->where('office_part.qty_iptn_out','>', 0)
					->whereRaw('office_part.qty > office_part.qty_in_ts')
					->get();

            foreach($part as $quotaion){
                $inventory = new Inventorys;
				$inventory->transaction_id		= $transaction->id;
				$inventory->company_id			= $user->company_id;
				$inventory->item_code			= $quotaion->part_id;
				$inventory->item_name           = $quotaion->part_name;
				$inventory->item_unit           = $quotaion->part_unit;
				$inventory->qty                 = -($quotaion->qty_iptn_out);
				$inventory->item_group        	= substr($quotaion->part_id,0,3);
				$inventory->office_part_id		= $quotaion->id;

				$cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
				if($cek_price){
					$inventory->price 			= -($cek_price->avg_price);
				}
				$inventory->total_price    	 	= -($quotaion->qty_iptn_out * $inventory->price);

				$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
							->where('TransactionType', 6)
							->where('InventAccountType', 1)
							->first();

				$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
							->where('TransactionType', 6)
							->where('InventAccountType', 2)
							->first();

				if($cekdebet)	{
					$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
				}else{
					$debet = Inventory_postings::where('TransactionType', 6)
								->where('InventAccountType', 1)
								->first();
					$inventory->ledgerAccount = $debet->LedgerAccountId;
				}

				if($cekcredit)	{
					$inventory->offsetAccount = $cekcredit->LedgerAccountId;
				}else{
					$credit = Inventory_postings::where('TransactionType', 6)
								->where('InventAccountType', 2)
								->first();
					$inventory->offsetAccount = $credit->LedgerAccountId;
				}

				$inventory->save();

				$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
				//var_dump($cekstock);
				if($cekstock){
					$total_lama = $cekstock->qty-($quotaion->qty_iptn_out);
					$total_price = $cekstock->price-($quotaion->qty_iptn_out * $inventory->price);

					$inventory->currentQty		= $cekstock->qty-$quotaion->qty_iptn_out;
					$inventory->currentValue	= $cekstock->price-($quotaion->qty_iptn_out * $inventory->price);

					$cekstock->item_code		= $quotaion->part_id;
					$cekstock->item_name		= $quotaion->part_name;
					$cekstock->item_unit		= $quotaion->part_unit;;
					$cekstock->location_id		= $user_location_id;
					$cekstock->warehouse_id	    = $user_Warehouse_id;
					$cekstock->qty				= $cekstock->qty-$quotaion->qty_iptn_out;
					if($total_lama == 0){
						$cekstock->avg_price	= 0;
					}else{
						$cekstock->avg_price	= $total_price/$total_lama;
					}
					$cekstock->price			= $cekstock->price-($cekstock->qty * $inventory->price);
					$cekstock->save();
				}

				$inventory->save();

				if($transaction->iptn_id>0){
					if (($quotaion->qty_iptn_out !=0) and ($quotaion->qty_iptn_out < $quotaion->qty)) {

						$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
						if($price_list3) {
							$price_list3->qty_out				= $price_list3->qty_out+$quotaion->qty_iptn_out;
							$price_list3->save();

							$office_part = Office_parts::find($quotaion->id);
							if($office_part){
								$office_part->stock 			= $office_part->stock - ($quotaion->qty_iptn_out);
								$office_part->qty_out_cs	 	= $office_part->qty_out_cs + $quotaion->qty_iptn_out;
								$office_part->iptn_out_ts_id 	= $inventory->id;

								$office_part->save();
							}

						}

					} else if (($quotaion->qty_iptn_out !=0) and ($quotaion->qty_iptn_out >= $quotaion->qty)) {

						$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
						if($price_list3) {
							$price_list3->qty_out      		= $price_list3->qty_out+$quotaion->qty_iptn_out;
							$price_list3->is_transfer_out   = 1;
							$price_list3->save();

							$office_part = Office_parts::find($quotaion->id);

							if($office_part){
								$office_part->stock 		 = $office_part->stock - ($quotaion->qty_iptn_out);
								$office_part->qty_out_cs	 = $office_part->qty_out_cs + $quotaion->qty_iptn_out;
								$office_part->iptn_out_ts_id = $inventory->id;
								$office_part->is_active		 = 4; // Transfered from CS

								$office_part->save();
							}
						}
					}

				}


				//--------------------------------------------edit status purchase order----------------------------------------------------------//
				if($quotaion->iptn_id){
					$out = $quotaion->iptn_id;
					$receive_out = Iptns::find($quotaion->iptn_id);
					if($receive_out){
						$query = Iptns::select('iptn.*')
									->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
									->where('iptn.id','=', $out)
									->get();
						$query_data = $query->toArray();
						$jmlh = count ($query_data);
						//var_dump($jmlh);

						$query1 =Iptns::select('iptn.*')
								->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
								->where('iptn.id','=', $out)
								->where('iptn_item.is_transfer_out','=',1)
								->get();
						$query_data1 = $query1->toArray();
						$jmlh2 = count ($query_data1);

						if ($jmlh == $jmlh2){
							$receive_out->is_submitted = 7;
							$receive_out->save();
						} else {
							$receive_out->is_submitted = 6;
							$receive_out->save();
						}
					}
				}
            }

            $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
			if(!$delete_inventory) {
				$transaction_delete = Transactions::find($transaction->id);
				$transaction_delete->delete();
			}

            $warehouse = Warehouses::find($transaction->warehouse_id);
			$a =  Transactions::select('id, code')
				->where('id', '=', $transaction->id);

			$debitQuery = DB::table('inventory')
                ->select(
                    'ledgerAccount as LedgerAccountId',
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    'offsetAccount as LedgerAccountId',
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $ledgerQuery = DB::table(
                DB::raw('(' . $a->toSql() . ') items')
            )
            ->mergeBindings($a->getQuery())
            ->select('items.*, datas.*')
            ->leftJoin(
                DB::raw('(' . $unionQuery->toSql() . ') datas'),
                function ($join) {
                    $join->on('datas.transaction_id', '=', 'items.id');
                }
            )
            ->where('datas.total', '!=', 0)
            ->get();

            if($ledgerQuery->count() > 0){
                // Proses ledger transaction
                foreach ($ledgerQuery as $quotaion1) {
                    $ledger = new Ledger_transactions();

                    $ledger->accountNum = $quotaion1->LedgerAccountId;
                    $ledger->transaction_date = $transaction->date_transaction;
                    $ledger->voucher = $transaction->code;
                    $ledger->amount = $quotaion1->total;
                    $ledger->currency = "IDR";
                    $ledger->rate = 1;
                    $ledger->total_basic = $quotaion1->total;
                    $ledger->dimention1 = $warehouse->dimention1;
                    $ledger->dimention2 = $warehouse->dimention2;
                    $ledger->dimention3 = $warehouse->dimention3;
                    $ledger->company_id = $transaction->company_id;
                    $ledger->user_id = $transaction->user_id;
                    $ledger->transaction_id = $transaction->id;

                    if ($transaction->explanation != null) {
                        $ledger->text = $transaction->explanation;
                    }

                    $ledger->save();
                }
            }

            $ipto = Transactions::where('id',$transaction->id)->first();

			$transaction1 = new Transactions;

			$transaction1->transaction_type       = 7;
			$transaction1->date_receive			 = date("Y-m-d");
			$transaction1->date_use				 = date("Y-m-d");
			$transaction1->user_id 				 = $user->id;
			$transaction1->location_id 			 = $ipto->location_to_id;
			$transaction1->warehouse_id			 = $ipto->warehouse_receive_id;
			$transaction1->company_id			 = $user->company_id;
			$transaction1->iptn_out_id	 		 = $ipto->id;
			$transaction1->iptn_out_code	 	 = $ipto->code;
			$transaction1->code     			 = Transactions::getNextCodeIPTIN();
			$transaction1->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
			$transaction1->explanation 			 = $cs->code.' (Receive part from CS)';
			$transaction1->approval 			 = $user->name;
			$transaction1->is_service			 = 1;
			$transaction1->cs_id			 	 = $id;

			$transaction1->save();

			$transaction1->date_transaction = $transaction1->created_at;

			$transaction1->save();

            $part1 = Inventorys::select()
						->join('transaction','transaction.id','=','inventory.transaction_id')
						->where('inventory.transaction_id', '=', $transaction->id)
						->where('transaction.is_service', '=', 1)
						->where('transaction.cs_id', '=', $id)
						->get();

            foreach($part1 as $quotaion2){
                $inventory1 = new Inventorys;
				$inventory1->transaction_id          = $transaction1->id;
				$inventory1->company_id              = $user->company_id;
				$inventory1->item_code               = $quotaion2->item_code;
				$inventory1->item_name               = $quotaion2->item_name;
				$inventory1->item_unit               = $quotaion2->item_unit;
				$inventory1->qty                     = -($quotaion2->qty);
				$inventory1->price                   = -($quotaion2->price);
				$inventory1->total_price             = ($quotaion2->qty * $quotaion2->price);
				$inventory1->item_group              = substr($quotaion2->item_code,0,3);
				$inventory1->office_part_id			 = $quotaion2->office_part_id;

				$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
									->where('TransactionType', 7)
									->where('InventAccountType', 1)
									->first();

				$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
											->where('TransactionType', 7)
											->where('InventAccountType', 2)
											->first();

				if($cekdebet)   {
					$inventory1->ledgerAccount = $cekdebet->LedgerAccountId;
				}else{
					$debet = Inventory_postings::where('TransactionType', 7)
											->where('InventAccountType', 1)
											->first();
					$inventory1->ledgerAccount = $debet->LedgerAccountId;
				}

				if($cekcredit)  {
					$inventory1->offsetAccount = $cekcredit->LedgerAccountId;
				}else{
					$credit = Inventory_postings::where('TransactionType', 7)
											->where('InventAccountType', 2)
											->first();

					$inventory1->offsetAccount = $credit->LedgerAccountId;
				}

				$inventory1->save();

				$cekstock = Stocks::where('item_code', $quotaion2->item_code)->where('warehouse_id', $user_Warehouse_id)->first();

				if($cekstock){
				$inventory1->currentQty                 = -($quotaion2->qty);
				$inventory1->currentValue               = $cekstock->price+($quotaion2->qty * $quotaion2->price);
				$cekstock->item_code                    = $quotaion2->item_code;
				$cekstock->item_name                    = $quotaion2->item_name;
				$cekstock->item_unit                    = $quotaion2->item_unit;
				$cekstock->location_id                  = $ipto->location_to_id;
				$cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
				$cekstock->qty                          = $cekstock->qty+(-($quotaion2->qty));
				$cekstock->price                        = $cekstock->price+($quotaion2->qty * $quotaion2->price);
				if($cekstock->qty>0){
				$cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
				}else{
				$cekstock->avg_price                    =0;
				}
				$cekstock->save();
				}else{
				$cekstock = new Stocks;
				$inventory1->currentQty                 = -($quotaion2->qty);
				$inventory1->currentValue               = ($quotaion2->qty * $quotaion2->price);
				$cekstock->item_code                    = $quotaion2->item_code;
				$cekstock->item_name                    = $quotaion2->item_name;
				$cekstock->item_unit                    = $quotaion2->item_unit;
				$cekstock->location_id                  = $ipto->location_to_id;
				$cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
				$cekstock->qty                          = -($quotaion2->qty);
				$cekstock->price                        = ($quotaion2->qty * $quotaion2->price);
				if($cekstock->qty>0){
				$cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
				}else{
				$cekstock->avg_price                    =0;
				}
				$cekstock->save();
				}

				$inventory1->save();

				$iptn_out = Inventorys::where('transaction_id',$id)
							->where('item_code',$quotaion2->item_code)
							->first();

				if($iptn_out) {
					$iptn_out->is_iptn_in       = 1;
					$iptn_out->save();
				}

				$office_part = Office_parts::find($quotaion2->office_part_id);
				if($office_part){
					$office_part->iptn_in_ts_id	= $inventory1->id;
					$office_part->stock_ts 		= $office_part->stock_ts + -($quotaion2->qty);
					$office_part->qty_in_ts		= $office_part->qty_in_ts + -($quotaion2->qty);
					$office_part->is_active 	= 5; // Part Ready on TS

					$office_part->save();
				}
            }

            $delete_inventory = Inventorys::where('transaction_id', $transaction1->id)->first();
			if(!$delete_inventory) {
				$transaction_delete = Transactions::find($transaction1->id);
				$transaction_delete->save();

				$transaction_delete->delete();
			}

			//----------------------------------------update transaction iptn out---------------------------------------------//

			$cek_iptn_out =  Transactions::where('id', $ipto->id)->first();
			if($cek_iptn_out){
				$cek_iptn_out->is_iptn_in	=	1;
				$cek_iptn_out->save();
			}

            $warehouse1 = Warehouses::find($transaction1->warehouse_id);
            $c = Transactions::select('id', 'code')->whereId($transaction1->id);

            $debitQuery = DB::table('inventory')
                ->select(
                    'ledgerAccount as LedgerAccountId',
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction1->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    'offsetAccount as LedgerAccountId',
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction1->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $d = DB::table(
                DB::raw('(' . $c->toSql() . ') items')
            )
            ->mergeBindings($c->getQuery())
            ->select('items.*, datas.*')
            ->leftJoin(
                DB::raw('(' . $unionQuery->toSql() . ') datas'),
                function ($join1) {
                    $join1->on('datas.transaction_id', '=', 'items.id');
                }
            )
            ->where('datas.total', '!=', 0)
            ->get();

            if($d->count() > 0){
                foreach($d as $quotaion3){
                    $ledger1 = new Ledger_transactions();

                    $ledger1->accountNum = $quotaion3->LedgerAccountId;
					$ledger1->transaction_date = $transaction1->date_transaction;
					$ledger1->voucher = $transaction1->code;
					$ledger1->amount = $quotaion3->total;
					$ledger1->currency = "IDR";
					$ledger1->rate = 1;
					$ledger1->total_basic = $quotaion3->total;
					$ledger1->dimention1 = $warehouse1->dimention1;
					$ledger1->dimention2 = $warehouse1->dimention2;
					$ledger1->dimention3 = $warehouse1->dimention3;
					$ledger1->company_id = $transaction1->company_id;
					$ledger1->user_id = $transaction1->user_id;
					$ledger1->transaction_id = $transaction1->id;
					if($transaction1->explanation != null){
						$ledger1->text 			= $transaction1->explanation;
					}

					$ledger1->save();
                }
            }

        }
        if($type == 4){
            $cs_item = Office_cs_items::where('cs_id','=',$id)->first();

			$cs_obj = Office_css::where('id','=',$id)->first();

			$customer = Customers::where('id','=',$cs_obj->customer_id)->first();

			$qontak_auth = Auth_token_qontak_whatsapps::where("is_active","=",1)->first();

            			$curl = curl_init();

			curl_setopt_array($curl, [
			CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
				'to_number' => "$customer->phone",
				'to_name' => "$customer->name",
				'message_template_id' => 'e3798057-46f2-4e3f-886b-0b7146750152',
				'channel_integration_id' => 'd84787ce-41a7-4c5b-a992-e080f89008c9',
				'language' => [
					'code' => 'id'
				],
				'parameters' => [
					'body' => [
						[
							'key' => '1',
							'value' => 'full_name',
							'value_text' => "$customer->contact"
						],
						[
							'key' => '2',
							'value' => 'company',
							'value_text' => "$customer->name"
						],
						[
							'key' => '3',
							'value' => 'no_service',
							'value_text' => "$cs_obj->code"
						]
					]
				]
			]),
			CURLOPT_HTTPHEADER => [
				"Authorization: $qontak_auth->authorization_type $qontak_auth->authorization_value",
				"Content-Type: application/json"
			],
			]);

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				echo $response;
			}

			if($cs_item){
				$cs_item->is_active  	  = 3;
				$cs_item->technician_id   = $technician_id;

				if($requests['status'] == 1){
					$cs_item->time_done_ts = date("Y-m-d H:i:s");
					$cs_item->user_done_ts = $user->id;
					$cs_item->user_done = $user->id;
				}elseif($requests['status'] == 2){
					$cs_item->time_done_ts = date("Y-m-d H:i:s");
					$cs_item->user_done_ts = $user->id;
					$cs_item->user_done = $user->id;

					if($cs_item->req_part == 0){
						$cs_item->req_part 			= 0;
						$cs_item->req_part_done 	= 0;
					}else{
						$cs_item->req_part 			= 1;
						$cs_item->req_part_done 	= 1;
					}
				}

				$cs_item->save();
			}

			$cs = Office_css::find($id);
			$cs->is_active  = 7;
			$cs->is_done_ts = 0;
			$cs->save();
        }
        if($type == 5){
            $cs_item = Office_cs_items::whereId($id)->first();
			if($cs_item){
				if($requests['status'] == 1){
					$cs_item->time_done = date("Y-m-d H:i:s");
				}elseif($requests['status'] == 2){
					$cs_item->time_done = date("Y-m-d H:i:s");
				}

				$cs_item->save();
			}

			if($requests['status'] == 1){
				//var_dump('a');
				$cs = Office_css::find($id);
				$cs->is_active  = 7;
				$cs->is_done_ts = 1;
				$cs->save();
			}else{
				//======================Transfer To Temporary & Bad Part========================
				$cs = Office_css::find($id);
				$cs->is_active  = 7;
				$cs->is_done_ts = 1;
				$cs->save();

				//var_dump('b');
				if($cs_item->warranty==1){
					$cek_whs = Warehouses::where('flag',1)->where('is_warranty',1)->first();
				}else{
					$cek_whs = Warehouses::where('flag',1)->first();
				}
				//$cek_whs_bp = Warehouses::select()->where('id',$cs->warehouse_id)->first();


				$part = Office_parts::where('cs_id',$id)->where('is_active','>',4)->where('is_active','<',9)->get();

				$whs_ts = Office_parts::selectRaw('office_part.warehouse_ts_id as warehouse_id,
							(select location_id from user where user.id=office_part.user_ts) as location_id')
							->where('cs_id',$id)
							->where('is_active','>',4)
							->where('is_active','<',9)
							->first();

				if($part->count() > 0){

					//----------------IPTN Out To Temporary-------------------------
					$transaction = new Transactions;

					$transaction->transaction_type       = 6;
					$transaction->user_id 				 = $user->id;
					$transaction->location_id 			 = $whs_ts->location_id;
					$transaction->warehouse_id			 = $whs_ts->warehouse_id;
					$transaction->company_id			 = $user->company_id;
					$transaction->location_to_id 		 = $cek_whs->location_id;
					$transaction->warehouse_receive_id	 = $cek_whs->id;
					$transaction->code     				 = Transactions::getNextCodeIPTO();
					$transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
					$transaction->explanation 			 = $cs->code.' (Move Spare Part from TS to Temporary)';
					$transaction->is_service 			 = 1;
					$transaction->cs_id					 = $id;
					$transaction->approval 				 = $user->name;

					$transaction->save();

					$transaction->date_transaction = $transaction->created_at;

					$transaction->save();

                    foreach($part as $quotaion){

                        $inventory = new Inventorys;

                        $inventory->transaction_id		= $transaction->id;
                        $inventory->company_id			= $user->company_id;
                        $inventory->item_code        	= $quotaion->part_id;
                        $inventory->item_name        	= $quotaion->part_name;
                        $inventory->qty        			= -($quotaion->qty_iptn_out);
                        $inventory->item_unit        	= $quotaion['part_unit'];
                        $inventory->item_group        	= substr($quotaion->part_id,0,3);
                        $cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $whs_ts->warehouse_id)->first();
                        if($cek_price){
                            $inventory->price 			= -($cek_price->avg_price);
                        }
                        $inventory->total_price    	 	= -($quotaion->qty_iptn_out * $inventory->price);
                        $inventory->office_part_id 		= $quotaion->id;

                        $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
                                    ->where('TransactionType', 6)
                                    ->where('InventAccountType', 1)
                                    ->first();

                        $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
                                    ->where('TransactionType', 6)
                                    ->where('InventAccountType', 2)
                                    ->first();

                        if($cekdebet)	{
                            $inventory->ledgerAccount = $cekdebet->LedgerAccountId;
                        }else{
                            $debet = Inventory_postings::where('TransactionType', 6)
                                        ->where('InventAccountType', 1)
                                        ->first();
                            $inventory->ledgerAccount = $debet->LedgerAccountId;
                        }

                        if($cekcredit)	{
                            $inventory->offsetAccount = $cekcredit->LedgerAccountId;
                        }else{
                            $credit = Inventory_postings::where('TransactionType', 6)
                                        ->where('InventAccountType', 2)
                                        ->first();
                            $inventory->offsetAccount = $credit->LedgerAccountId;
                        }

                        $inventory->save();

                        $cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $whs_ts->warehouse_id)->first();
                        //var_dump($cekstock);
                        if($cekstock){
                            $total_lama = $cekstock->qty-($quotaion->qty_iptn_out);
                            $total_price = $cekstock->price-($quotaion->qty_iptn_out * $inventory->price);

                            $inventory->currentQty		= $cekstock->qty-$quotaion->qty_iptn_out;
                            $inventory->currentValue	= $cekstock->price-($quotaion->qty_iptn_out * $inventory->price);

                            $cekstock->item_code		= $quotaion->part_id;
                            $cekstock->item_name		= $quotaion->part_name;
                            $cekstock->item_unit		= $quotaion->part_unit;
                            $cekstock->location_id		= $whs_ts->location_id;
                            $cekstock->warehouse_id	    = $whs_ts->warehouse_id;
                            $cekstock->qty				= $cekstock->qty-$quotaion->qty_iptn_out;
                            if($total_lama == 0){
                                $cekstock->avg_price	= 0;
                            }else{
                                $cekstock->avg_price	= $total_price/$total_lama;
                            }
                            $cekstock->price			= $cekstock->price-($cekstock->qty * $inventory->price);
                            $cekstock->save();
                        }

                        $inventory->save();
                    }

					$delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();

					if(!$delete_inventory) {
	    				$transaction_delete = Transactions::find($transaction->id);
    					$transaction_delete->delete();
					}

					//-------------------------------Ledger Transaction------------------------------------//
					$warehouse = Warehouses::find($transaction->warehouse_id);
					$a =  Transactions::select('id, code')
						->where('id', '=', $transaction->id);

                    $debitQuery = DB::table('inventory')
                        ->select(
                            'ledgerAccount as LedgerAccountId',
                            'transaction_id',
                            'item_group',
                            DB::raw('SUM(-total_price) as total')
                        )
                        ->where('transaction_id', $transaction->id)
                        ->groupBy('ledgerAccount');

                    $creditQuery = DB::table('inventory')
                        ->select(
                            'offsetAccount as LedgerAccountId',
                            'transaction_id',
                            'item_group',
                            DB::raw('SUM(total_price) as total')
                        )
                        ->where('transaction_id', $transaction->id)
                        ->groupBy('offsetAccount');

                    $unionQuery = $debitQuery->union($creditQuery);

                    $b = DB::table(
                        DB::raw('(' . $a->toSql() . ') items')
                    )
                    ->mergeBindings($a->getQuery())
                    ->select('items.*, datas.*')
                    ->leftJoin(
                        DB::raw('(' . $unionQuery->toSql() . ') datas'),
                        function ($join) {
                            $join->on('datas.transaction_id', '=', 'items.id');
                        }
                    )
                    ->where('datas.total', '!=', 0)
                    ->get();


					if($b->count() > 0){
                        foreach($b as $quotaion1){
                            $ledger = new Ledger_transactions();

                            $ledger->accountNum = $quotaion1->LedgerAccountId;
                            $ledger->transaction_date = $transaction->date_transaction;
                            $ledger->voucher = $transaction->code;
                            $ledger->amount = $quotaion1->total;
                            $ledger->currency = "IDR";
                            $ledger->rate = 1;
                            $ledger->total_basic = $quotaion1->total;
                            $ledger->dimention1 = $warehouse->dimention1;
                            $ledger->dimention2 = $warehouse->dimention2;
                            $ledger->dimention3 = $warehouse->dimention3;
                            $ledger->company_id = $transaction->company_id;
                            $ledger->user_id = $transaction->user_id;
                            $ledger->transaction_id = $transaction->id;
                            if($transaction->explanation != null){
                                $ledger->text 			= $transaction->explanation;
                            }
                            $ledger->save();
                        }
					}


					//----------------IPTN IN In Temporary-------------------------
					$transaction2 = new Transactions;

					$transaction2->transaction_type       = 7;
					$transaction2->date_receive			 = date("Y-m-d");
					$transaction2->date_use				 = date("Y-m-d");
					$transaction2->user_id 				 = $user->id;
					$transaction2->location_id 			 = $cek_whs->location_id;
					$transaction2->warehouse_id			 = $cek_whs->id;
					$transaction2->company_id			 = $user->company_id;
					$transaction2->iptn_out_id	 		 = $transaction->id;
					$transaction2->iptn_out_code		 = $transaction->code;
					$transaction2->code     			 = Transactions::getNextCodeIPTIN();
					$transaction2->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
					$transaction2->explanation 			 = $cs->code.' (Receive spare part from TS)';
					$transaction2->is_service 			 = 1;
					$transaction2->cs_id				 = $id;
					$transaction2->approval 			 = $user->name;

					$transaction2->save();

					$transaction2->date_transaction = $transaction2->created_at;

					$transaction2->save();

                    foreach($part as $quotaion2){

                    }

					for ($j=0; $j < $part->count() ; $j++) {
						$quotaion2 = (array) $parts[$j];

						$inventory2 = new Inventorys;

						$inventory2->transaction_id		= $transaction2->id;
						$inventory2->company_id			= $user->company_id;
						$inventory2->item_code        	= $quotaion2['part_id'];
						$inventory2->item_name        	= $quotaion2['part_name'];
						$inventory2->qty        		= ($quotaion2['qty_iptn_out']);
						$inventory2->item_unit        	= $quotaion2['part_unit'];
						$inventory2->item_group        	= substr($quotaion2['part_id'],0,3);
						/* $cek_price = Stocks::where('item_code', $inventory2->item_code)->where('warehouse_id', $user->warehouse_id)->first();
						if($cek_price){
							$inventory2->price 			= $cek_price->avg_price;
						} */
						$inventory2->price 				= $inventory->price;
						$inventory2->total_price    	= -($quotaion2['qty_iptn_out'] * $inventory2->price);
						$inventory2->office_part_id 	= $quotaion2['id'];

						$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2['part_id'],0,3))
						->where('TransactionType', 7)
						->where('InventAccountType', 1)
						->first();

						$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2['part_id'],0,3))
						->where('TransactionType', 7)
						->where('InventAccountType', 2)
						->first();

						if($cekdebet){
							$inventory2->ledgerAccount = $cekdebet->LedgerAccountId;
						}else{
							$debet = Inventory_postings::where('TransactionType', 7)
										->where('InventAccountType', 1)
										->first();
							$inventory2->ledgerAccount = $debet->LedgerAccountId;
						}

						if($cekcredit)	{
							$inventory2->offsetAccount = $cekcredit->LedgerAccountId;
						}else{
							$credit = Inventory_postings::where('TransactionType', 7)
										->where('InventAccountType', 2)
										->first();
							$inventory2->offsetAccount = $credit->LedgerAccountId;
						}

						$inventory2->save();

						$cekstock = Stocks::where('item_code', $inventory2->item_code)->where('warehouse_id', $cek_whs->id)->first();

						if($cekstock){
							$inventory2->currentQty					= $cekstock->qty+$quotaion2['qty_iptn_out'];
							$inventory2->currentValue				= $cekstock->price+($quotaion2['qty_iptn_out'] * (-1*$inventory2->price));
							$cekstock->item_code					= $quotaion2['part_id'];;
							$cekstock->item_name					= $quotaion2['part_name'];
							$cekstock->item_unit					= $quotaion2['part_unit'];
							$cekstock->location_id					= $cek_whs->location_id;
							$cekstock->warehouse_id					= $cek_whs->id;
							$cekstock->qty							= $cekstock->qty+$quotaion2['qty_iptn_out'];
							$cekstock->price						= $cekstock->price+($quotaion2['qty_iptn_out'] * (-1*$inventory2->price));
							if($cekstock->qty>0){
							$cekstock->avg_price					= ($cekstock->price)/($cekstock->qty);
							}else{
							$cekstock->avg_price					=0;
							}
							$cekstock->save();
						}else{
							$cekstock = new Stocks;
							$inventory2->currentQty					= $quotaion2['qty_iptn_out'];
							$inventory2->currentValue				= ($quotaion2['qty_iptn_out'] * (-1*$inventory2->price));
							$cekstock->item_code					= $quotaion2['part_id'];;
							$cekstock->item_name					= $quotaion2['part_name'];
							$cekstock->item_unit					= $quotaion2['part_unit'];
							$cekstock->location_id					= $cek_whs->location_id;
							$cekstock->warehouse_id					= $cek_whs->id;
							$cekstock->qty							= $quotaion2['qty_iptn_out'];
							$cekstock->price						= ($quotaion2['qty_iptn_out'] * (-1*$inventory2->price));
							if($cekstock->qty>0){
							$cekstock->avg_price					= ($cekstock->price)/($cekstock->qty);
							}else{
							$cekstock->avg_price					=0;
							}
							$cekstock->save();
						}

						$inventory2->save();
					}
					if (isset($quotaion2['qty_iptn_out']) || $quotaion2['qty_iptn_out'] !=0) {
						$iptn_out = Inventorys::where('transaction_id',$transaction->id)
										->where('item_code',$quotaion2['part_id'])
										->first();

						if($iptn_out) {
							$iptn_out->is_iptn_in      	= 1;
							$iptn_out->save();
						}
					}

					$delete_inventory2 = Inventorys::where('transaction_id', $transaction2->id)->first();

					if(!$delete_inventory2) {
						$transaction_delete2 = Transactions::find($transaction2->id);

						$transaction_delete2->delete();
					}

					//----------------------------------------update transaction iptn out---------------------------------------------//

					$cek_iptn_out =  Transactions::whereId($transaction2->id)->first();

					if($cek_iptn_out){
						$cek_iptn_out->is_iptn_in	=	1;
						$cek_iptn_out->save();
					}

					//----------------------------------------------------------------------------------------------//

					//-------------------------------Ledger Transaction------------------------------------//
					$warehouse2 = Warehouses::find($transaction2->warehouse_id);
					$c =  Transactions::select('id, code')
						->where('id', '=', $transaction2->id);

                    $debitQuery = DB::table('inventory')
                        ->select(
                            'ledgerAccount as LedgerAccountId',
                            'transaction_id',
                            'item_group',
                            DB::raw('SUM(total_price) as total')
                        )
                        ->where('transaction_id', $transaction->id)
                        ->groupBy('ledgerAccount');

                    $creditQuery = DB::table('inventory')
                        ->select(
                            'offsetAccount as LedgerAccountId',
                            'transaction_id',
                            'item_group',
                            DB::raw('SUM(-total_price) as total')
                        )
                        ->where('transaction_id', $transaction->id)
                        ->groupBy('offsetAccount');

                    $unionQuery = $debitQuery->union($creditQuery);

                    $d = DB::table(
                        DB::raw('(' . $a->toSql() . ') items')
                    )
                    ->mergeBindings($a->getQuery())
                    ->select('items.*, datas.*')
                    ->leftJoin(
                        DB::raw('(' . $unionQuery->toSql() . ') datas'),
                        function ($join) {
                            $join->on('datas.transaction_id', '=', 'items.id');
                        }
                    )
                    ->where('datas.total', '!=', 0)
                    ->get();

					$tot2 = count ($d);

					if($tot2 > 0){
						for ($q=0; $q < $tot2 ; $q++) {
							$quotaion3 = (array) $d[$q];

							$ledger2 = new Ledger_transactions;

							$ledger2->accountNum = $quotaion3['LedgerAccountId'];
							$ledger2->transaction_date = $transaction2->date_transaction;
							$ledger2->voucher = $transaction2->code;
							$ledger2->amount = $quotaion3['total'];
							$ledger2->currency = "IDR";
							$ledger2->rate = 1;
							$ledger2->total_basic = $quotaion3['total'];
							$ledger2->dimention1 = $warehouse2->dimention1;
							$ledger2->dimention2 = $warehouse2->dimention2;
							$ledger2->dimention3 = $warehouse2->dimention3;
							$ledger2->company_id = $transaction2->company_id;
							$ledger2->user_id = $transaction2->user_id;
							$ledger2->transaction_id = $transaction2->id;
							if($transaction2->explanation != null){
								$ledger2->text 			= $transaction2->explanation;
							}

							$ledger2->save();
						}
					}


					//----------------Adjust IN Bad Part in Temporary Bad Part TS-------------------------
					$whs_bp = Warehouses::where('flag',5)->where('is_active',1)->first();

					$transaction3 = new Transactions;

					$transaction3->transaction_type     = 23;
					$transaction3->user_id              = $user->id;
					$transaction3->location_id          = $whs_bp->location_id;
					$transaction3->warehouse_id         = $whs_bp->id;
					$transaction3->company_id           = $user->company_id;
					$transaction3->code                 = Transactions::getNextCodeAdjustment();
					$transaction3->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
					$transaction3->adjustment_type 		= 1;
					$transaction3->explanation 			= "Receive Bad Part in Temporary Bad Part TS For CS No. ".$cs->code;
					$transaction3->is_service 			 = 1;
					$transaction3->cs_id				 = $id;

					$mytime = Carbon::now();
					$transaction3->date_required 		= $mytime->toDateTimeString();

					$transaction3->save();

					$transaction3->date_transaction 	= $transaction3->created_at;

					$transaction3->save();

					for ($k=0; $k < $part->count() ; $k++) {
						$quotaion4 = (array) $parts[$k];

						$inventory3 = new Inventorys;
						$inventory3->transaction_id          = $transaction3->id;
						$inventory3->company_id              = $user->company_id;
						$inventory3->item_code               = $quotaion4['part_id'];
						$inventory3->item_name               = $quotaion4['part_name'];
						$inventory3->qty                     = ($quotaion4['qty_iptn_out']);
						$inventory3->item_unit               = $quotaion4['part_unit'];
						$inventory3->item_group              = substr($quotaion4['part_id'],0,3);
						/* $cek_price = Stocks::where('item_code', $inventory3->item_code)->where('warehouse_id', $user->warehouse_id)->first();
						if($cek_price){
							$inventory3->price 				= $cek_price->avg_price;
						} */
						// $inventory3->price 				= $inventory->price;
						$inventory3->price 					= 0;
						$inventory3->total_price    		= ($quotaion4['qty_iptn_out'] * $inventory3->price);
						$inventory3->office_part_id 		= $quotaion4['id'];

						$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion4['part_id'],0,3))
										->where('TransactionType', 23)
										->where('InventAccountType', 1)
										->first();

						$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion4['part_id'],0,3))
										->where('TransactionType', 5)
										->where('InventAccountType', 1)
										->first();

						if($cekdebet)   {
							$inventory3->ledgerAccount = $cekdebet->LedgerAccountId;
						}

						if($cekcredit)  {
							$inventory3->offsetAccount = $cekcredit->LedgerAccountId;
						}

						$inventory3->save();

						$cekstock = Stocks::where('item_code', $inventory3->item_code)->where('warehouse_id', $whs_bp->id)->first();
						//var_dump($cekstock);
						if($cekstock){
							$total_lama = $cekstock->qty+$quotaion4['qty_iptn_out'];
							$total_price = $cekstock->price+($quotaion4['qty_iptn_out'] * $inventory3->price);

							$inventory3->currentQty          = $cekstock->qty+$quotaion4['qty_iptn_out'];
							$inventory3->currentValue        = $cekstock->price+($quotaion4['qty_iptn_out'] * $inventory3->price);

							$cekstock->item_code            = $quotaion4['part_id'];
							$cekstock->item_name            = $quotaion4['part_name'];
							$cekstock->item_unit            = $quotaion4['part_unit'];
							$cekstock->location_id          = $whs_bp->location_id;
							$cekstock->warehouse_id         = $whs_bp->id;
							$cekstock->qty                  = $cekstock->qty+$quotaion4['qty_iptn_out'];
							$cekstock->price                = $cekstock->price+$inventory3->total_price;
							if($total_lama == 0){
								$cekstock->avg_price        = 0;
							}else{
								$cekstock->avg_price        = $total_price/$total_lama;
							}
							$cekstock->save();
						}else{
							$cekstock = new Stocks;
							$inventory3->currentQty          = $quotaion4['qty_iptn_out'];
							$inventory3->currentValue        = $quotaion4['qty_iptn_out'] * $inventory3->price;

							$cekstock->item_code            = $quotaion4['part_id'];
							$cekstock->item_name            = $quotaion4['part_name'];
							$cekstock->item_unit            = $quotaion4['part_unit'];
							$cekstock->location_id          = $whs_bp->location_id;
							$cekstock->warehouse_id         = $whs_bp->id;
							$cekstock->qty                  = $quotaion4['qty_iptn_out'];
							$cekstock->price                = $inventory3->total_price;
							$cekstock->avg_price            = ($cekstock->price)/($cekstock->qty);
							$cekstock->save();
						}

						$inventory3->save();
					}

					$delete_inventory3 = Inventorys::where('transaction_id', $transaction3->id)->first();

					if(!$delete_inventory3) {
						$transaction_delete = Transactions::find($transaction3->id);
						$transaction_delete->delete();
					}

					//-------------------------------Ledger Transaction------------------------------------//

					$warehouse3 = Warehouses::find($transaction3->warehouse_id);
					$e =  Transactions::select('id, code')
							->where('id', '=', $transaction3->id);

                    $debitQuery = DB::table('inventory')
                        ->select(
                            'ledgerAccount as LedgerAccountId',
                            'transaction_id',
                            'item_group',
                            DB::raw('SUM(total_price) as total')
                        )
                        ->where('transaction_id', $transaction->id)
                        ->groupBy('ledgerAccount');

                    $creditQuery = DB::table('inventory')
                        ->select(
                            'offsetAccount as LedgerAccountId',
                            'transaction_id',
                            'item_group',
                            DB::raw('SUM(-total_price) as total')
                        )
                        ->where('transaction_id', $transaction->id)
                        ->groupBy('offsetAccount');

                    $unionQuery = $debitQuery->union($creditQuery);

                    $f = DB::table(
                        DB::raw('(' . $a->toSql() . ') items')
                    )
                    ->mergeBindings($a->getQuery())
                    ->select('items.*, datas.*')
                    ->leftJoin(
                        DB::raw('(' . $unionQuery->toSql() . ') datas'),
                        function ($join) {
                            $join->on('datas.transaction_id', '=', 'items.id');
                        }
                    )
                    ->where('datas.total', '!=', 0)
                    ->get();

					$tot4 = count ($f);

					if($tot4 > 0){
						for ($r=0; $r < $tot4 ; $r++) {
							$quotaion5 = (array) $f[$r];

							$ledger3 = new Ledger_transactions;

							$ledger3->accountNum = $quotaion5['LedgerAccountId'];
							$ledger3->transaction_date = $transaction3->date_transaction;
							$ledger3->voucher = $transaction3->code;
							$ledger3->amount = $quotaion5['total'];
							$ledger3->currency = "IDR";
							$ledger3->rate = 1;
							$ledger3->total_basic = $quotaion5['total'];
							$ledger3->dimention1 = $warehouse3->dimention1;
							$ledger3->dimention2 = $warehouse3->dimention2;
							$ledger3->dimention3 = $warehouse3->dimention3;
							$ledger3->company_id = $transaction3->company_id;
							$ledger3->user_id = $transaction3->user_id;
							$ledger3->transaction_id = $transaction3->id;
							if($transaction3->explanation != null){
								$ledger3->text           = $transaction3->explanation;
							}

							$ledger3->save();

						}
					}
					$update_part = Office_parts::where('cs_id', '=', $id)->where('is_active', '<', 9)->update(array('stock_ts' => 0,'is_active'=>8));
				}
			}
        }
        if($type == 6){
            $cs = Office_css::find($id);
            if($cs->is_active < 4){
                $cs->is_active = 4;
                $cs->save();
            }

            $transaction = new Transactions;
			$transaction->transaction_type       = 7;
			$transaction->date_receive			 = date("Y-m-d");
			$transaction->date_use				 = date("Y-m-d");
			$transaction->user_id 				 = $user->id;
			$transaction->location_id 			 = $cs->location_id;
			$transaction->warehouse_id			 = $cs->warehouse_id;
			$transaction->company_id			 = $cs->company_id;
			$transaction->iptn_out_id	 		 = $requests['ipto_id'];
			$transaction->iptn_out_code	 		 = $requests['ipto_code'];
			$transaction->code     				 = Transactions::getNextCodeIPTIN();
			$transaction->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
			$transaction->explanation 			 = $cs->code.' (Receive Part from Warehouse)';
			$transaction->approval 				 = $user->name;
			$transaction->is_service			 = 1;
			$transaction->cs_id			 		 = $id;

			$transaction->save();

			$transaction->date_transaction = $transaction->created_at;

			$transaction->save();

			//----------------inventory--------------------
			$cek_part = Office_parts::select($app->db->raw('office_part.*, sum(-inventory.qty) as tot_qty_ipto, sum(if(inventory.is_iptn_in>0, (-inventory.qty), 0)) as tot_qty_iptn_in'))
						->join('inventory','inventory.office_part_id','=','office_part.id')
						->join('transaction','transaction.id','=','inventory.transaction_id')
						->where('office_part.cs_id', '=', $id)
						->where('office_part.id', '=', $requests['office_part_id'])
						->where('transaction.transaction_type',6)
						->where('transaction.warehouse_receive_id',$transaction->warehouse_id)
						->groupBy('office_part.cs_id')
						->first();

			if($cek_part->qty >= $cek_part->tot_qty_ipto && ($cek_part->tot_qty_iptn_in==0 || $cek_part->tot_qty_iptn_in >= $cek_part->qty_in_cs) ){

				$ipto = Inventorys::select($app->db->raw('inventory.*'))
						->join('transaction','transaction.id','=','inventory.transaction_id')
						->where('inventory.office_part_id', '=', $requests['office_part_id'])
						->where('transaction.transaction_type',6)
						->where('transaction.id', '=', $requests['ipto_id'])
						->where('transaction.cs_id', '=', $id)
						->where('transaction.warehouse_receive_id',$transaction->warehouse_id)
						->first();

				$inventory = new Inventorys;
				$inventory->transaction_id          = $transaction->id;
				$inventory->company_id              = $transaction->company_id;
				$inventory->product_code            = $cek_part->product_code;
				$inventory->item_code               = $cek_part->part_id;
				$inventory->item_name               = $cek_part->part_name;
				$inventory->item_unit               = $cek_part->part_unit;
				if($requests['qty_iptn_in'] == $requests['qty_whs']){
				$inventory->qty                     = $requests['qty_iptn_in'];
				}else{
				$inventory->qty                     = $requests['qty_whs'];
				}
				$inventory->price                   = -($ipto->price);
				$inventory->total_price             = $inventory->qty * $inventory->price;
				$inventory->item_group              = substr($inventory->item_code,0,3);
				$inventory->office_part_id			= $requests['office_part_id'];

				$cekdebet = Inventory_postings::where('ItemRelation', substr($inventory->item_code,0,3))
									->where('TransactionType', 7)
									->where('InventAccountType', 1)
									->first();

				$cekcredit = Inventory_postings::where('ItemRelation', substr($inventory->item_code,0,3))
								->where('TransactionType', 7)
								->where('InventAccountType', 2)
								->first();

				if($cekdebet){
					$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
				}else{
					$debet = Inventory_postings::where('TransactionType', 7)
								->where('InventAccountType', 1)
								->first();
					$inventory->ledgerAccount = $debet->LedgerAccountId;
				}

				if($cekcredit){
					$inventory->offsetAccount = $cekcredit->LedgerAccountId;
				}else{
					$credit = Inventory_postings::where('TransactionType', 7)
								->where('InventAccountType', 2)
								->first();
					$inventory->offsetAccount = $credit->LedgerAccountId;
				}

				$inventory->save();

				$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $transaction->warehouse_id)->first();

				if($cekstock){
				$inventory->currentQty                  = $inventory->qty;
				$inventory->currentValue                = $cekstock->price+($inventory->qty * $inventory->price);
				$cekstock->item_code                    = $inventory->item_code;
				$cekstock->item_name                    = $inventory->item_name;
				$cekstock->item_unit                    = $inventory->item_unit;
				$cekstock->location_id                  = $transaction->location_id;
				$cekstock->warehouse_id                 = $transaction->warehouse_id;
				$cekstock->qty                          = $cekstock->qty+$inventory->qty;
				$cekstock->price                        = $cekstock->price+($inventory->qty * $inventory->price);
				if($cekstock->qty>0){
				$cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
				}else{
				$cekstock->avg_price                    = 0;
				}
				$cekstock->save();
				}else{
				$cekstock = new Stocks;
				$inventory->currentQty                  = $inventory->qty;
				$inventory->currentValue                = ($inventory->qty * $inventory->price);
				$cekstock->item_code                    = $inventory->item_code;
				$cekstock->item_name                    = $inventory->item_name;
				$cekstock->item_unit                    = $inventory->item_unit;
				$cekstock->location_id                  = $transaction->location_id;
				$cekstock->warehouse_id                 = $transaction->warehouse_id;
				$cekstock->qty                          = $inventory->qty;
				$cekstock->price                        = ($inventory->qty * $inventory->price);
				if($cekstock->qty>0){
				$cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
				}else{
				$cekstock->avg_price                    = 0;
				}
				$cekstock->save();
				}

				$inventory->save();

				$iptn_out = Inventorys::where('transaction_id',$transaction->iptn_out_id)
							->where('item_code',$inventory->item_code)
							->first();
				if($iptn_out) {
					$iptn_out->is_iptn_in = 1;
					$iptn_out->save();
				}

				$office_part = Office_parts::find($requests['office_part_id']);
				if($office_part){
					$office_part->stock 			= $office_part->stock + $inventory->qty;
					$office_part->qty_in_cs			= $office_part->qty_in_cs + $inventory->qty;
					$office_part->iptn_in_cs_id		= $inventory->id;

					if($office_part->qty_in_cs >= $office_part->qty){
						$office_part->qty_in_cs 	= $office_part->qty;
						$office_part->qty_iptn_in 	= $office_part->qty;
						if($office_part->is_active < 3){
						$office_part->is_active 	= 3; //Part Ready on CS
						}
					}

					$office_part->save();
				}
			}

			$delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
			if(!$delete_inventory) {
				$transaction_delete = Transactions::find($transaction->id);
				$transaction_delete->save();

				$transaction_delete->delete();
			}

			//----------------------------------------update transaction iptn out---------------------------------------------//

			$cek_iptn_out_item = Inventorys::select($app->db->raw('count(id) as tot_item, sum(if(is_iptn_in>0, 1, 0)) as tot_iptn_in'))->where('transaction_id', $transaction->iptn_out_id)->first();
			if($cek_iptn_out_item->tot_item == $cek_iptn_out_item->tot_iptn_in){
				$cek_iptn_out =  Transactions::where('id', $transaction->iptn_out_id)->first();
				if($cek_iptn_out){
					$cek_iptn_out->is_iptn_in	=	1;
					$cek_iptn_out->save();
				}
			}

			//-------------------------------Ledger Transaction------------------------------------//
			$warehouse = Warehouses::find($transaction->warehouse_id);
			$a =  Transactions::select('id, code')->where('id', '=', $transaction->id);

            $debitQuery = DB::table('inventory')
                ->select(
                    'ledgerAccount as LedgerAccountId',
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    'offsetAccount as LedgerAccountId',
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $b = DB::table(
                DB::raw('(' . $a->toSql() . ') items')
            )
            ->mergeBindings($a->getQuery())
            ->select('items.*, datas.*')
            ->leftJoin(
                DB::raw('(' . $unionQuery->toSql() . ') datas'),
                function ($join) {
                    $join->on('datas.transaction_id', '=', 'items.id');
                }
            )
            ->where('datas.total', '!=', 0)
            ->get();

			$tot = count ($b);

			if($tot > 0){
				for ($k=0; $k < $tot ; $k++) {
					$quotaion1 = (array) $b[$k];

					$ledger = new Ledger_transactions;

					$ledger->accountNum = $quotaion1['LedgerAccountId'];
					$ledger->transaction_date = $transaction->date_transaction;
					$ledger->voucher = $transaction->code;
					$ledger->amount = $quotaion1['total'];
					$ledger->currency = "IDR";
					$ledger->rate = 1;
					$ledger->total_basic = $quotaion1['total'];
					$ledger->dimention1 = $warehouse->dimention1;
					$ledger->dimention2 = $warehouse->dimention2;
					$ledger->dimention3 = $warehouse->dimention3;
					$ledger->company_id = $transaction->company_id;
					$ledger->user_id = $transaction->user_id;
					$ledger->transaction_id = $transaction->id;
					if($transaction->explanation != null){
						$ledger->text 			= $transaction->explanation;
					}

					$ledger->save();
				}
			}
        }
        return new OfficeCsResource(true, 'Draft adjustment in warranty (spare part) success', null);
    }

    public function email(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'cs_item_id' => 'required',
            'customer_id' => 'required'
        ]);

        $user = auth()->guard('api')->user();
        $company =Companys::find($user->company_id);

        // Update status CS Item
        $cs_item = Office_cs_items::find($request->cs_item_id);
        if ($cs_item) {
            $cs_item->email_send = 1;
            $cs_item->date_email_send = date("Y-m-d H:i:s");
            $cs_item->save();

            $cs = Office_css::find($cs_item->cs_id);
            if ($cs) {
                $cs->is_active = 4;
                $cs->save();
            }
        }

        // Ambil customer
        $customer = Customers::find($request->customer_id);

        // Generate PDF untuk WA dan Email (gunakan DomPDF)
        $pdf_data = [
            'cs_data' => $cs_item,
            'company' => $company,
            // tambahkan data lain yang dibutuhkan oleh view PDF
        ];

        $pdfLocation = $this->pdfController->makeCustomerServicePart($request->item_id);

        // Kirim WhatsApp via Qontak
        $qontakAuth = Auth_token_qontak_whatsapps::where("is_active", 1)->first();
        // dd($qontakAuth);
        $response = Http::withHeaders([
            'Authorization' => "{$qontakAuth->authorization_type} {$qontakAuth->authorization_value}",
            'Content-Type' => 'application/json',
            ])->post('https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct', [
            'to_number' => $customer->phone,
            'to_name' => $customer->name,
            'message_template_id' => '291fc502-0d75-49f6-9e41-05eb5bff45c5',
            'channel_integration_id' => 'd84787ce-41a7-4c5b-a992-e080f89008c9',
            'language' => ['code' => 'id'],
            'parameters' => [
                "header" => [
                    "format" => "DOCUMENT",
                    "params" => [
                        [
                            "key" => "url",
                            "value" => env('BASE_URL') . 'pdf_save/media/biaya_perbaikan_' . $request->cs_item_id . '.pdf'
                        ],
                        [
                            "key" => "filename",
                            "value" => "biaya_perbaikan_" . $request->cs_item_id
                        ]
                    ]
                ],
                'body' => [
                    [
                        'key' => '1',
                        'value' => 'full_name',
                        'value_text' => $customer->contact
                    ],
                    [
                        'key' => '2',
                        'value' => 'company',
                        'value_text' => $customer->name
                    ],
                    [
                        'key' => '3',
                        'value' => 'lampiran',
                        'value_text' => "biaya_perbaikan_" . $request->item_id . '.pdf'
                    ]
                ]
            ]
        ]);


        if ($response->failed()) {
            return response()->json(['error' => 'Failed to send WhatsApp message'], 500);
        }

        $emailSent = $this->mailer->sendEmailCs($customer->email, $customer->name, $pdfLocation, $company, $request->item_id);

        // dd($emailSent);
        if($emailSent){
            return new OfficeCsResource(true, 'Email sent successfully', $emailSent);
        }

        return new OfficeCsResource(false, 'Failed to send email', null);

    }

    public function approveCust(Request $request)
    {
        $requests = $request->all();

        $cs_item = Office_cs_items::find($requests['id']);
		if($cs_item){
			$cs_item->approval_cust = $requests['approval_cust'];
			$cs_item->cancel_note = $requests['cancel_note'];
			$cs_item->save();

			$cs = Office_css::select()->where('id',$cs_item->cs_id)->first();
			if($cs){
				$cs->is_active = 4;
				$cs->save();
			}
		}

        if($cs_item){
            return new OfficeCsResource(true, 'Success', $cs_item);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function approveCustWithId($id, Request $request, $cancel_type)
    {
        $requests = $request->all();
        $cs_item = Office_cs_items::find($id);
        $cs = Office_css::whereId($cs_item->cs_id)->first();
        $user = auth()->guard('api')->user();
        $user_Location_id = $user->location_id;
        $user_Warehouse_id = $user->warehouse_id;

        if($cs_item){
            if($cancel_type > 0){
                if($cancel_type == 1 || $cancel_type == 3){
                    $cost = Office_cost_items::select('office_cost_item.cancel_cost')
                    ->join('office_cost', 'office_cost.id', '=', 'office_cost_item.cost_id')
					->join('office_customer_item', 'office_customer_item.item_code','=','office_cost_item.item_code')
					->join('office_cs_item', 'office_customer_item.id', '=', 'office_cs_item.item_id')
					->where('office_cs_item.id', $id)
					->where('office_cost.is_active', 1)
					->where('office_cost.is_submitted', 4)
					->first();

                    $cs_item->cost = $cost->cancel_cost;

                    if($cancel_type == 3){
                        if($cs_item->req_part > 0){
                            $cek_part = Office_parts::selectRaw(
                                'count(office_part.id) as tot_part,
								(case when office_part.is_active = 0 then count(office_part.id) else 0 end) as tot_waiting,
								case when office_part.is_active = 1 then count(office_part.id) else 0 end) as tot_request_whs,
								(case when office_part.is_active = 2 then count(office_part.id) else 0 end) as tot_transf_whs,
								(case when office_part.is_active = 3 then count(office_part.id) else 0 end) as tot_on_cs,
								(case when office_part.is_active = 4 then count(office_part.id) else 0 end) as tot_transf_cs,
								(case when office_part.is_active = 5 then count(office_part.id) else 0 end) as tot_on_ts,
								(case when office_part.is_active = 8 then count(office_part.id) else 0 end) as tot_done,
								(case when office_part.is_active = 9 then count(office_part.id) else 0 end) as tot_cancel'
                            )
                            ->where('office_part.cs_id', $cs_item->cs_id)
                            ->groupBy('office_part.cs_id')
                            ->first();

                            if($cs->is_active == 4 && $cek_part->tot_request_whs > 0){
                                $cek_iptn = Iptns::select('iptn.warehouse_id')
                                            ->where('iptn.warehouse_id_receipt', $cs->warehouse_id)
                                            ->where('iptn.is_active', 1)
                                            ->where('iptn.cs_id', $cs_item->cs_id)
                                            ->first();

                                $update_iptn = Iptns::whereId($cek_iptn->id)->update(['is_submitted' => 7]);

                                $update_iptn_item = Iptn_items::where('iptn_id', $cek_iptn->id)->update(['is_transfer_out' => 1]);
                            } else if($cs->is_active == 4 && $cek_part->tot_trasf_whs > 0){
                                $ipto = Transactions::where('transaction_type', 6)->where('is_service', 1)->where('cs_id', $cs_item->cs_id)->where('warehouse_receive_id', $cs->warehouse_id)->where('is_iptn_in', null)->get();

                                foreach($ipto as $iptos){
                                    $transaction = new Transactions();

                                    $transaction->transaction_type = 7;
                                    $transaction->date_receive = date("Y-m-d");
                                    $transaction->date_use = date("Y-m-d");
                                    $transaction->user_id = $user->id;
                                    $transaction->location_id 			 = $user_Location_id;
									$transaction->warehouse_id			 = $user_Warehouse_id;
									$transaction->company_id			 = $user->company_id;
									$transaction->iptn_out_id	 		 = $iptos->id;
									$transaction->iptn_out_code	 		 = $iptos->code;
									$transaction->code     				 = Transactions::getNextCodeIPTIN();
									$transaction->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
									$transaction->explanation 			 = $cs->code.' (CANCEL (Transfer WHS) - Receive Part from Warehouse)';
									$transaction->approval 				 = $user->name;
									$transaction->is_service			 = 1;
									$transaction->cs_id			 		 = $cs_item->cs_id;

									$transaction->save();

									$transaction->date_transaction = $transaction->created_at;

									$transaction->save();

                                    $parts = Office_parts::selectRaw(' office_part.*, inventory.qty as ipto_qty, inventory.price as ipto_price')
                                            ->join('inventory','inventory.office_part_id','=','office_part.id')
											->where('office_part.cs_id', '=', $cs_item->cs_id)
											->where('office_part.iptn_in_cs_id','=', 0)
											->where('office_part.qty_iptn_in','>', 0)
											->where('office_part.is_active','<', 9)
											->whereRaw('office_part.qty <= office_part.qty_iptn_in')
											->where('inventory.transaction_id',$transaction->iptn_out_id)
											->get();

                                    foreach($parts as $part){
                                        $inventory = new Inventorys();
										$inventory->transaction_id          = $transaction->id;
										$inventory->company_id              = $user->company_id;
										$inventory->item_code               = $part->part_id;
										$inventory->item_name               = $part['part_name'];
										$inventory->item_unit               = $part->part_unit;
										$inventory->qty                     = $part->ipto_qty;
										$inventory->price                   = -($part->ipto_price);
										$inventory->total_price             = -($part->ipto_qty * $part->ipto_price);
										$inventory->item_group              = substr($part->part_id,0,3);
										$inventory->office_part_id			= $part->id;

                                        $cekdebet = Inventory_postings::where('ItemRelation', substr($part->part_id, 0, 3))
                                                    ->where('TransactionType', 7)
                                                    ->where('InventAccountType', 1)
                                                    ->first();

                                        $cekcredit = Inventory_postings::where('ItemRelation', substr($part->part_id, 0, 3))
                                                    ->where('TransactionType', 7)
                                                    ->where('InventAccountType', 2)
                                                    ->first();

                                        if($cekdebet){
                                            $inventory->ledgerAccount = $cekdebet->LedgerAccountId;
                                        }else{
                                            $debet = Inventory_postings::where('TransactionType', 7)
                                                    ->where('InventAccountType', 1)
                                                    ->first();

                                            $inventory->ledgerAccount = $debet->LedgerAccountId;
                                        }

                                        if($cekcredit){
                                            $inventory->offsetAccount = $cekcredit->LedgerAccountId;
                                        }else{
                                            $credit = Inventory_postings::where('TransactionType', 7)
                                                    ->where('InventAccountType', 2)
                                                    ->first();

                                            $inventory->offsetAccount = $credit->LedgerAccountId;
                                        }

                                        $inventory->save();

                                        $cekstock = Stocks::where('item_code', $part->part_id)->where('warehouse_id', $user_Warehouse_id)->first();

                                        if($cekstock){
                                            $inventory->currentQty = $part->ipto_qty;
                                            $inventory->currentValue = $cekstock->price + ($part->ipto_qty * -($part->ipto_price));
                                            $cekstock->item_code = $part->part_id;
                                            $cekstock->item_name = $part->part_name;
                                            $cekstock->item_unit = $part->part_unit;
                                            $cekstock->location_id = $user_Location_id;
                                            $cekstock->warehouse_id = $user_Warehouse_id;
                                            $cekstock->qty = $cekstock->qty + $part->ipto_qty;
                                            $cekstock->price = $cekstock->price + ($part->ipto_qty * -($part->ipto_price));

                                            if($cekstock->qty > 0){
                                                $cekstock->avg_price = ($cekstock->price)/($cekstock->qty);
                                            }else{
                                                $cekstock->avg_price = 0;
                                            }

                                            $cekstock->save();
                                        }else{
                                            $cekstock = new Stocks;
                                            $inventory->currentQty                  = $part->ipto_qty;
                                            $inventory->currentValue                = ($part->ipto_qty * -($part->ipto_price));
                                            $cekstock->item_code                    = $part->part_id;
                                            $cekstock->item_name                    = $part->part_name;
                                            $cekstock->item_unit                    = $part->part_unit;
                                            $cekstock->location_id                  = $user_Location_id;
                                            $cekstock->warehouse_id                 = $user_Warehouse_id;
                                            $cekstock->qty                          = $part->ipto_qty;
                                            $cekstock->price                        = ($part->ipto_qty * -($part->ipto_price));
                                            if($cekstock->qty>0){
                                                $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                                            }else{
                                                $cekstock->avg_price                    =0;
                                            }
                                            $cekstock->save();
                                        }

                                        $inventory->save();

                                        $iptn_out = Inventorys::where('transaction_id', $transaction->iptn_out_id)
                                                    ->where('item_code', $part->part_id)
                                                    ->first();

                                        if($iptn_out){
                                            $iptn_out->is_iptn_in = 1;
                                            $iptn_out->save();
                                        }

                                        $office_part = Office_parts::find($part->id);

                                        if($office_part){
											$office_part->stock 		= $office_part->stock + ($part->ipto_qty);
											$office_part->qty_in_cs		= $office_part->qty_in_cs + ($part->ipto_qty);
											$office_part->iptn_in_cs_id = $inventory->id;
											$office_part->is_active 	= 3; //Part Ready on CS

											$office_part->save();
										}
                                    }

                                    $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
                                    if(!$delete_inventory){
                                        $transaction_delete = Transactions::find($transaction->id);
                                        $transaction_delete->delete();
                                    }

                                    $cek_iptn_out = Transactions::whereId($transaction->iptn_out_id)->first();
                                    if($cek_iptn_out){
                                        $cek_iptn_out->is_iptn_in = 1;
                                        $cek_iptn_out->save();
                                    }

                                    $warehouse = Warehouses::find($transaction->warehouse_id);
                                    $a = Transactions::select(DB::raw('id, code'))
                                        ->where('id', $transaction->id);

                                    // Subquery untuk gabung data inventory
                                    $subquery = DB::raw("(
                                        SELECT * FROM (
                                            SELECT
                                                ledgerAccount AS LedgerAccountId,
                                                transaction_id,
                                                item_group,
                                                SUM(total_price) AS total
                                            FROM inventory
                                            WHERE transaction_id = {$transaction->id}
                                            GROUP BY ledgerAccount, transaction_id, item_group

                                            UNION

                                            SELECT
                                                offsetAccount AS LedgerAccountId,
                                                transaction_id,
                                                item_group,
                                                SUM(-total_price) AS total
                                            FROM inventory
                                            WHERE transaction_id = {$transaction->id}
                                            GROUP BY offsetAccount, transaction_id, item_group
                                        ) AS c
                                    ) AS datas");

                                    // Eksekusi query utama
                                    $result = DB::table(DB::raw("({$a->toSql()}) AS items"))
                                        ->mergeBindings($a->getQuery()) // penting untuk menjaga binding dari $a
                                        ->select(DB::raw('items.*, datas.*'))
                                        ->leftJoin($subquery, function ($join) {
                                            $join->on('datas.transaction_id', '=', 'items.id');
                                        })
                                        ->where('datas.total', '!=', 0)
                                        ->get();

                                    if($result->count() > 0){
                                        foreach($result as $quotaion1){
                                            $ledger = new Ledger_transactions();

											$ledger->accountNum = $quotaion1->LedgerAccountId;
											$ledger->transaction_date = $transaction->date_transaction;
											$ledger->voucher = $transaction->code;
											$ledger->amount = $quotaion1->total;
											$ledger->currency = "IDR";
											$ledger->rate = 1;
											$ledger->total_basic = $quotaion1->total;
											$ledger->dimention1 = $warehouse->dimention1;
											$ledger->dimention2 = $warehouse->dimention2;
											$ledger->dimention3 = $warehouse->dimention3;
											$ledger->company_id = $transaction->company_id;
											$ledger->user_id = $transaction->user_id;
											$ledger->transaction_id = $transaction->id;
											if($transaction->explanation != null){
												$ledger->text 			= $transaction->explanation;
											}

											$ledger->save();
                                        }
                                    }
                                }
                            } else if($cs->is_active == 4 && $cek_part->tot_on_ts > 0){
                                $cek_whs = Users::selectRaw('user.location_id, user.warehouse_id')
                                            ->join('office_technician', 'office_technician.user_id', '=', 'user.id')
                                            ->where('office_technician.id', $cs_item->technician_id)
                                            ->first();

                                $transaction = new Transactions();

                                $transaction->transaction_type       = 6;
								$transaction->user_id 				 = $user->id;
								$transaction->location_id 			 = $cek_whs->location_id;
								$transaction->warehouse_id			 = $cek_whs->warehouse_id;
								$transaction->company_id			 = $user->company_id;
								$transaction->location_to_id 		 = $cs->location_id;
								$transaction->warehouse_receive_id	 = $cs->warehouse_id;
								$transaction->code     				 = Transactions::getNextCodeIPTO();
								$transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
								$transaction->explanation 			 = $cs->code.' (CANCEL (Done TS) - Send part from TS to CS)';
								$transaction->approval 				 = $user->name;
								$transaction->is_service			 = 1;
								$transaction->cs_id			 	 	 = $cs_item->cs_id;

								$transaction->save();

								$transaction->date_transaction = $transaction->created_at;

								$transaction->save();

                                $parts = Office_parts::select('office_part.*')
                                        ->where('office_part.cs_id', $cs_item->cs_id)
                                        ->where('office_part.is_active','<', 9)
										->get();

                                foreach($parts as $quotaion){
                                    $inventory = new Inventorys();
									$inventory->transaction_id		= $transaction->id;
									$inventory->company_id			= $user->company_id;
									$inventory->item_code			= $quotaion->part_id;
									$inventory->item_name           = $quotaion->part_name;
									$inventory->item_unit           = $quotaion->part_unit;
									$inventory->qty                 = -($quotaion->qty_in_ts);
									$inventory->item_group        	= substr($quotaion->part_id,0,3);
									$inventory->office_part_id		= $quotaion->id;

									$cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
									if($cek_price){
										$inventory->price 			= -($cek_price->avg_price);
									}
									$inventory->total_price    	 	= -($quotaion->qty_in_ts * $inventory->price);

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
												->where('TransactionType', 6)
												->where('InventAccountType', 1)
												->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
												->where('TransactionType', 6)
												->where('InventAccountType', 2)
												->first();

									if($cekdebet)	{
										$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
									}else{
										$debet = Inventory_postings::where('TransactionType', 6)
													->where('InventAccountType', 1)
													->first();
										$inventory->ledgerAccount = $debet->LedgerAccountId;
									}

									if($cekcredit)	{
										$inventory->offsetAccount = $cekcredit->LedgerAccountId;
									}else{
										$credit = Inventory_postings::where('TransactionType', 6)
													->where('InventAccountType', 2)
													->first();
										$inventory->offsetAccount = $credit->LedgerAccountId;
									}

									$inventory->save();

									$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
									//var_dump($cekstock);
									if($cekstock){
										$total_lama = $cekstock->qty-($quotaion->qty_in_ts);
										$total_price = $cekstock->price-($quotaion->qty_in_ts * $inventory->price);

										$inventory->currentQty		= $cekstock->qty-$quotaion->qty_in_ts;
										$inventory->currentValue	= $cekstock->price-($quotaion->qty_in_ts * $inventory->price);

										$cekstock->item_code		= $quotaion->part_id;
										$cekstock->item_name		= $quotaion->part_name;
										$cekstock->item_unit		= $quotaion->part_unit;;
										$cekstock->location_id		= $user_Location_id;
										$cekstock->warehouse_id	    = $user_Warehouse_id;
										$cekstock->qty				= $cekstock->qty-$quotaion->qty_in_ts;
										if($total_lama == 0){
											$cekstock->avg_price	= 0;
										}else{
											$cekstock->avg_price	= $total_price/$total_lama;
										}
										$cekstock->price			= $cekstock->price-($cekstock->qty * $inventory->price);
										$cekstock->save();
									}

									$inventory->save();

                                    if($transaction->iptn_id>0){
										if (($quotaion->qty_in_ts !=0) and ($quotaion->qty_in_ts < $quotaion->qty)) {

											$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
											if($price_list3) {
												$price_list3->qty_out				= $price_list3->qty_out+$quotaion->qty_in_ts;
												$price_list3->save();

												$office_part = Office_parts::find($quotaion->id);
												if($office_part){
													$office_part->stock 			= $office_part->stock - ($quotaion->qty_in_ts);
													$office_part->qty_out_cs	 	= $office_part->qty_out_cs + $quotaion->qty_in_ts;
													$office_part->iptn_out_ts_id 	= $inventory->id;

													$office_part->save();
												}

											}

										} else if (($quotaion->qty_in_ts !=0) and ($quotaion->qty_in_ts >= $quotaion->qty)) {

											$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
											if($price_list3) {
												$price_list3->qty_out      		= $price_list3->qty_out+$quotaion->qty_in_ts;
												$price_list3->is_transfer_out   = 1;
												$price_list3->save();

												$office_part = Office_parts::find($quotaion->id);

												if($office_part){
													$office_part->stock 		 = $office_part->stock - ($quotaion->qty_in_ts);
													$office_part->qty_out_cs	 = $office_part->qty_out_cs + $quotaion->qty_in_ts;
													$office_part->iptn_out_ts_id = $inventory->id;
													$office_part->is_active		 = 4; // Transfered from CS

													$office_part->save();
												}
											}
										}
									}
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
								if(!$delete_inventory) {
									$transaction_delete = Transactions::find($transaction->id);
									$transaction_delete->delete();
								}

                                $warehouse = Warehouses::find($transaction->warehouse_id);
                                $b = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                               transaction_id,
                                            item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                             item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                    // Eksekusi query utama
                                $result = DB::table(DB::raw("({$b->toSql()}) AS items"))
                                    ->mergeBindings($b->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result->count() > 0){
                                    foreach($result as $quotaion1){
                                        $ledger = new Ledger_transactions();

										$ledger->accountNum = $quotaion1->LedgerAccountId;
										$ledger->transaction_date = $transaction->date_transaction;
										$ledger->voucher = $transaction->code;
										$ledger->amount = $quotaion1->total;
										$ledger->currency = "IDR";
										$ledger->rate = 1;
										$ledger->total_basic = $quotaion1->total;
										$ledger->dimention1 = $warehouse->dimention1;
										$ledger->dimention2 = $warehouse->dimention2;
										$ledger->dimention3 = $warehouse->dimention3;
										$ledger->company_id = $transaction->company_id;
										$ledger->user_id = $transaction->user_id;
										$ledger->transaction_id = $transaction->id;
										if($transaction->explanation != null){
											$ledger->text 			= $transaction->explanation;
										}

										$ledger->save();
                                    }
                                }

                                $ipto = Transactions::select()->where('id',$transaction->id)->first();

								$transaction1 = new Transactions();

								$transaction1->transaction_type       = 7;
								$transaction1->date_receive			 = date("Y-m-d");
								$transaction1->date_use				 = date("Y-m-d");
								$transaction1->user_id 				 = $user->id;
								$transaction1->location_id 			 = $ipto->location_to_id;
								$transaction1->warehouse_id			 = $ipto->warehouse_receive_id;
								$transaction1->company_id			 = $user->company_id;
								$transaction1->iptn_out_id	 		 = $ipto->id;
								$transaction1->iptn_out_code	 	 = $ipto->code;
								$transaction1->code     			 = Transactions::getNextCodeIPTIN();
								$transaction1->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
								$transaction1->explanation 			 = $cs->code.' (CANCEL (Done TS) - Receive part from TS)';
								$transaction1->approval 			 = $user->name;
								$transaction1->is_service			 = 1;
								$transaction1->cs_id			 	 = $cs_item->cs_id;

								$transaction1->save();

								$transaction1->date_transaction = $transaction1->created_at;

								$transaction1->save();

                                $part1 = DB::table('inventory')
											->join('transaction','transaction.id','=','inventory.transaction_id')
											->where('inventory.transaction_id', '=', $transaction->id)
											->where('transaction.is_service', '=', 1)
											->where('transaction.cs_id', '=', $cs_item->cs_id)
											->get();

                                foreach($part1 as $quotaion2){
                                    $inventory1 = new Inventorys();
									$inventory1->transaction_id          = $transaction1->id;
									$inventory1->company_id              = $user->company_id;
									$inventory1->item_code               = $quotaion2->item_code;
									$inventory1->item_name               = $quotaion2->item_name;
									$inventory1->item_unit               = $quotaion2->item_unit;
									$inventory1->qty                     = -($quotaion2->qty);
									$inventory1->price                   = -($quotaion2->price);
									$inventory1->total_price             = ($quotaion2->qty * $quotaion2->price);
									$inventory1->item_group              = substr($quotaion2->item_code,0,3);
									$inventory1->office_part_id			 = $quotaion2->office_part_id;

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
														->where('TransactionType', 7)
														->where('InventAccountType', 1)
														->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
																->where('TransactionType', 7)
																->where('InventAccountType', 2)
																->first();

									if($cekdebet)   {
										$inventory1->ledgerAccount = $cekdebet->LedgerAccountId;
									}else{
										$debet = Inventory_postings::where('TransactionType', 7)
																->where('InventAccountType', 1)
																->first();
										$inventory1->ledgerAccount = $debet->LedgerAccountId;
									}

									if($cekcredit)  {
										$inventory1->offsetAccount = $cekcredit->LedgerAccountId;
									}else{
										$credit = Inventory_postings::where('TransactionType', 7)
																->where('InventAccountType', 2)
																->first();

										$inventory1->offsetAccount = $credit->LedgerAccountId;
									}

									$inventory1->save();

									$cekstock = Stocks::where('item_code', $quotaion2->item_code)->where('warehouse_id', $user_Warehouse_id)->first();

									if($cekstock){
                                        $inventory1->currentQty                 = -($quotaion2->qty);
                                        $inventory1->currentValue               = $cekstock->price+($quotaion2->qty * $quotaion2->price);
                                        $cekstock->item_code                    = $quotaion2->item_code;
                                        $cekstock->item_name                    = $quotaion2->item_name;
                                        $cekstock->item_unit                    = $quotaion2->item_unit;
                                        $cekstock->location_id                  = $ipto->location_to_id;
                                        $cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
                                        $cekstock->qty                          = $cekstock->qty+(-($quotaion2->qty));
                                        $cekstock->price                        = $cekstock->price+($quotaion2->qty * $quotaion2->price);
                                        if($cekstock->qty>0){
                                            $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                                        }else{
                                            $cekstock->avg_price                    =0;
                                        }
                                        $cekstock->save();
									}else{
                                        $cekstock = new Stocks();
                                        $inventory1->currentQty                 = -($quotaion2->qty);
                                        $inventory1->currentValue               = ($quotaion2->qty * $quotaion2->price);
                                        $cekstock->item_code                    = $quotaion2->item_code;
                                        $cekstock->item_name                    = $quotaion2->item_name;
                                        $cekstock->item_unit                    = $quotaion2->item_unit;
                                        $cekstock->location_id                  = $ipto->location_to_id;
                                        $cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
                                        $cekstock->qty                          = -($quotaion2->qty);
                                        $cekstock->price                        = ($quotaion2->qty * $quotaion2->price);
                                        if($cekstock->qty>0){
                                            $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                                        }else{
                                            $cekstock->avg_price                    =0;
                                        }
                                        $cekstock->save();
									}

									$inventory1->save();

									$iptn_out = Inventorys::where('transaction_id',$id)
												->where('item_code',$quotaion2->item_code)
												->first();

									if($iptn_out) {
										$iptn_out->is_iptn_in       = 1;
										$iptn_out->save();
									}

									$office_part = Office_parts::find($quotaion2->office_part_id);
									if($office_part){
										$office_part->iptn_in_ts_id	= $inventory1->id;
										$office_part->stock_ts 		= $office_part->stock_ts + -($quotaion2->qty);
										$office_part->qty_in_ts		= $office_part->qty_in_ts + -($quotaion2->qty);
										$office_part->is_active 	= 5; // Part Ready on TS

										$office_part->save();
									}
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction1->id)->first();
								if(!$delete_inventory) {
									$transaction_delete = Transactions::find($transaction1->id);

									$transaction_delete->delete();
								}

								$cek_iptn_out =  Transactions::where('id', $ipto->id)->first();
								if($cek_iptn_out){
									$cek_iptn_out->is_iptn_in	=	1;
									$cek_iptn_out->save();
								}

                                $warehouse1 = Warehouses::find($transaction->warehouse_id);
                                $c = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery1 = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                               transaction_id,
                                            item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction1->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                             item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction1->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                    // Eksekusi query utama
                                $result1 = DB::table(DB::raw("({$c->toSql()}) AS items"))
                                    ->mergeBindings($c->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery1, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result1->count() > 0){
                                    foreach($result1 as $quotaion3){
                                        $ledger1 = new Ledger_transactions();

										$ledger1->accountNum = $quotaion3->LedgerAccountId;
										$ledger1->transaction_date = $transaction1->date_transaction;
										$ledger1->voucher = $transaction1->code;
										$ledger1->amount = $quotaion3->total;
										$ledger1->currency = "IDR";
										$ledger1->rate = 1;
										$ledger1->total_basic = $quotaion3->total;
										$ledger1->dimention1 = $warehouse1->dimention1;
										$ledger1->dimention2 = $warehouse1->dimention2;
										$ledger1->dimention3 = $warehouse1->dimention3;
										$ledger1->company_id = $transaction1->company_id;
										$ledger1->user_id = $transaction1->user_id;
										$ledger1->transaction_id = $transaction1->id;
										if($transaction1->explanation != null){
											$ledger1->text 			= $transaction1->explanation;
										}
										$ledger1->save();
                                    }
                                }
                            } else if($cs->is_active == 7 && $cek_part->tot_done > 0 && $cs->is_done_ts == 0){
                                $cek_whs = Users::selectRaw('user.location_id, user.warehouse_id')
                                            ->join('office_technician','office_technician.user_id','=','user.id')
											->where('office_technician.id','=',$cs_item->technician_id)
											->first();

                                $transaction = new Transactions();

                                $transaction->transaction_type       = 6;
								$transaction->user_id 				 = $user->id;
								$transaction->location_id 			 = $cek_whs->location_id;
								$transaction->warehouse_id			 = $cek_whs->warehouse_id;
								$transaction->company_id			 = $user->company_id;
								$transaction->location_to_id 		 = $cs->location_id;
								$transaction->warehouse_receive_id	 = $cs->warehouse_id;
								$transaction->code     				 = Transactions::getNextCodeIPTO();
								$transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
								$transaction->explanation 			 = $cs->code.' (CANCEL (Part Ready on TS) - Send part from TS to CS)';
								$transaction->approval 				 = $user->name;
								$transaction->is_service			 = 1;
								$transaction->cs_id			 	 	 = $cs_item->cs_id;

								$transaction->save();

								$transaction->date_transaction = $transaction->created_at;

								$transaction->save();

                                $part = Office_parts::select('office_part.*')
                                        ->where('office_part.cs_id','=',$cs_item->cs_id)
										->where('office_part.is_active','<', 9)
										->get();

                                foreach($part as $quotaion){
                                    $inventory = new Inventorys();
									$inventory->transaction_id		= $transaction->id;
									$inventory->company_id			= $user->company_id;
									$inventory->item_code			= $quotaion->part_id;
									$inventory->item_name           = $quotaion->part_name;
									$inventory->item_unit           = $quotaion->part_unit;
									$inventory->qty                 = -($quotaion->qty_in_ts);
									$inventory->item_group        	= substr($quotaion->part_id,0,3);
									$inventory->office_part_id		= $quotaion->id;

									$cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
									if($cek_price){
										$inventory->price 			= -($cek_price->avg_price);
									}
									$inventory->total_price    	 	= -($quotaion->qty_in_ts * $inventory->price);

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
												->where('TransactionType', 6)
												->where('InventAccountType', 1)
												->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
												->where('TransactionType', 6)
												->where('InventAccountType', 2)
												->first();

									if($cekdebet)	{
										$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
									}else{
										$debet = Inventory_postings::where('TransactionType', 6)
													->where('InventAccountType', 1)
													->first();
										$inventory->ledgerAccount = $debet->LedgerAccountId;
									}

									if($cekcredit)	{
										$inventory->offsetAccount = $cekcredit->LedgerAccountId;
									}else{
										$credit = Inventory_postings::where('TransactionType', 6)
													->where('InventAccountType', 2)
													->first();
										$inventory->offsetAccount = $credit->LedgerAccountId;
									}

									$inventory->save();

									$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
									//var_dump($cekstock);
									if($cekstock){
										$total_lama = $cekstock->qty-($quotaion->qty_in_ts);
										$total_price = $cekstock->price-($quotaion->qty_in_ts * $inventory->price);

										$inventory->currentQty		= $cekstock->qty-$quotaion->qty_in_ts;
										$inventory->currentValue	= $cekstock->price-($quotaion->qty_in_ts * $inventory->price);

										$cekstock->item_code		= $quotaion->part_id;
										$cekstock->item_name		= $quotaion->part_name;
										$cekstock->item_unit		= $quotaion->part_unit;;
										$cekstock->location_id		= $user_Location_id;
										$cekstock->warehouse_id	    = $user_Warehouse_id;
										$cekstock->qty				= $cekstock->qty-$quotaion->qty_in_ts;
										if($total_lama == 0){
											$cekstock->avg_price	= 0;
										}else{
											$cekstock->avg_price	= $total_price/$total_lama;
										}
										$cekstock->price			= $cekstock->price-($cekstock->qty * $inventory->price);
										$cekstock->save();
									}

									$inventory->save();

									if($transaction->iptn_id>0){
										if (($quotaion->qty_in_ts !=0) and ($quotaion->qty_in_ts < $quotaion->qty)) {

											$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
											if($price_list3) {
												$price_list3->qty_out				= $price_list3->qty_out+$quotaion->qty_in_ts;
												$price_list3->save();

												$office_part = Office_parts::find($quotaion->id);
												if($office_part){
													$office_part->stock 			= $office_part->stock - ($quotaion->qty_in_ts);
													$office_part->qty_out_cs	 	= $office_part->qty_out_cs + $quotaion->qty_in_ts;
													$office_part->iptn_out_ts_id 	= $inventory->id;

													$office_part->save();
												}

											}

										} else if (($quotaion->qty_in_ts !=0) and ($quotaion->qty_in_ts >= $quotaion->qty)) {

											$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
											if($price_list3) {
												$price_list3->qty_out      		= $price_list3->qty_out+$quotaion->qty_in_ts;
												$price_list3->is_transfer_out   = 1;
												$price_list3->save();

												$office_part = Office_parts::find($quotaion->id);

												if($office_part){
													$office_part->stock 		 = $office_part->stock - ($quotaion->qty_in_ts);
													$office_part->qty_out_cs	 = $office_part->qty_out_cs + $quotaion->qty_in_ts;
													$office_part->iptn_out_ts_id = $inventory->id;
													$office_part->is_active		 = 4; // Transfered from CS

													$office_part->save();
												}
											}
										}
									}
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
								if(!$delete_inventory) {
									$transaction_delete = Transactions::find($transaction->id);

									$transaction_delete->delete();
								}

                                $warehouse = Warehouses::find($transaction->warehouse_id);
                                $a = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                            transaction_id,
                                            item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                            item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                // Eksekusi query utama
                                $result = DB::table(DB::raw("({$a->toSql()}) AS items"))
                                    ->mergeBindings($a->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result->count() > 0){
                                    foreach($result as $quotaion1){
                                        $ledger = new Ledger_transactions();

                                        $ledger->accountNum = $quotaion1->LedgerAccountId;
                                        $ledger->transaction_date = $transaction->date_transaction;
                                        $ledger->voucher = $transaction->code;
                                        $ledger->amount = $quotaion1->total;
                                        $ledger->currency = "IDR";
                                        $ledger->rate = 1;
                                        $ledger->total_basic = $quotaion1->total;
                                        $ledger->dimention1 = $warehouse->dimention1;
                                        $ledger->dimention2 = $warehouse->dimention2;
                                        $ledger->dimention3 = $warehouse->dimention3;
                                        $ledger->company_id = $transaction->company_id;
                                        $ledger->user_id = $transaction->user_id;
                                        $ledger->transaction_id = $transaction->id;
                                        if($transaction->explanation != null){
                                            $ledger->text 			= $transaction->explanation;
                                        }

                                        $ledger->save();
                                    }
                                }

                                $ipto = Transactions::whereId($transaction->id)->first();

                                $transaction1 = new Transactions();

								$transaction1->transaction_type       = 7;
								$transaction1->date_receive			 = date("Y-m-d");
								$transaction1->date_use				 = date("Y-m-d");
								$transaction1->user_id 				 = $user->id;
								$transaction1->location_id 			 = $ipto->location_to_id;
								$transaction1->warehouse_id			 = $ipto->warehouse_receive_id;
								$transaction1->company_id			 = $user->company_id;
								$transaction1->iptn_out_id	 		 = $ipto->id;
								$transaction1->iptn_out_code	 	 = $ipto->code;
								$transaction1->code     			 = Transactions::getNextCodeIPTIN();
								$transaction1->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
								$transaction1->explanation 			 = $cs->code.' (CANCEL (Part Ready on TS) - Receive part from TS)';
								$transaction1->approval 			 = $user->name;
								$transaction1->is_service			 = 1;
								$transaction1->cs_id			 	 = $cs_item->cs_id;

								$transaction1->save();

								$transaction1->date_transaction = $transaction1->created_at;

								$transaction1->save();

                                $part1 = DB::table('inventory')
                                        ->join('transaction','transaction.id','=','inventory.transaction_id')
										->where('inventory.transaction_id', '=', $transaction->id)
										->where('transaction.is_service', '=', 1)
										->where('transaction.cs_id', '=', $cs_item->cs_id)
										->get();

                                foreach($part1 as $quotaion2){
                                    $inventory1 = new Inventorys();
									$inventory1->transaction_id          = $transaction1->id;
									$inventory1->company_id              = $user->company_id;
									$inventory1->item_code               = $quotaion2->item_code;
									$inventory1->item_name               = $quotaion2->item_name;
									$inventory1->item_unit               = $quotaion2->item_unit;
									$inventory1->qty                     = -($quotaion2->qty);
									$inventory1->price                   = -($quotaion2->price);
									$inventory1->total_price             = ($quotaion2->qty * $quotaion2->price);
									$inventory1->item_group              = substr($quotaion2->item_code,0,3);
									$inventory1->office_part_id			 = $quotaion2->office_part_id;

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
														->where('TransactionType', 7)
														->where('InventAccountType', 1)
														->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
																->where('TransactionType', 7)
																->where('InventAccountType', 2)
																->first();

									if($cekdebet)   {
										$inventory1->ledgerAccount = $cekdebet->LedgerAccountId;
									}else{
										$debet = Inventory_postings::where('TransactionType', 7)
																->where('InventAccountType', 1)
																->first();
										$inventory1->ledgerAccount = $debet->LedgerAccountId;
									}

									if($cekcredit)  {
										$inventory1->offsetAccount = $cekcredit->LedgerAccountId;
									}else{
										$credit = Inventory_postings::where('TransactionType', 7)
																->where('InventAccountType', 2)
																->first();

										$inventory1->offsetAccount = $credit->LedgerAccountId;
									}

									$inventory1->save();

									$cekstock = Stocks::where('item_code', $quotaion2->item_code)->where('warehouse_id', $user_Warehouse_id)->first();

									if($cekstock){
                                        $inventory1->currentQty                 = -($quotaion2->qty);
                                        $inventory1->currentValue               = $cekstock->price+($quotaion2->qty * $quotaion2->price);
                                        $cekstock->item_code                    = $quotaion2->item_code;
                                        $cekstock->item_name                    = $quotaion2->item_name;
                                        $cekstock->item_unit                    = $quotaion2->item_unit;
                                        $cekstock->location_id                  = $ipto->location_to_id;
                                        $cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
                                        $cekstock->qty                          = $cekstock->qty+(-($quotaion2->qty));
                                        $cekstock->price                        = $cekstock->price+($quotaion2->qty * $quotaion2->price);
                                        if($cekstock->qty>0){
                                            $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                                        }else{
                                            $cekstock->avg_price                    =0;
                                        }
                                        $cekstock->save();
									}else{
                                        $cekstock = new Stocks();
                                        $inventory1->currentQty                 = -($quotaion2->qty);
                                        $inventory1->currentValue               = ($quotaion2->qty * $quotaion2->price);
                                        $cekstock->item_code                    = $quotaion2->item_code;
                                        $cekstock->item_name                    = $quotaion2->item_name;
                                        $cekstock->item_unit                    = $quotaion2->item_unit;
                                        $cekstock->location_id                  = $ipto->location_to_id;
                                        $cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
                                        $cekstock->qty                          = -($quotaion2->qty);
                                        $cekstock->price                        = ($quotaion2->qty * $quotaion2->price);
                                        if($cekstock->qty>0){
                                            $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                                        }else{
                                            $cekstock->avg_price                    =0;
                                        }
                                        $cekstock->save();
									}

									$inventory1->save();

									$iptn_out = Inventorys::where('transaction_id',$id)
												->where('item_code',$quotaion2->item_code)
												->first();

									if($iptn_out) {
										$iptn_out->is_iptn_in       = 1;
										$iptn_out->save();
									}

									$office_part = Office_parts::find($quotaion2->office_part_id);
									if($office_part){
										$office_part->iptn_in_ts_id	= $inventory1->id;
										$office_part->stock_ts 		= $office_part->stock_ts + -($quotaion2->qty);
										$office_part->qty_in_ts		= $office_part->qty_in_ts + -($quotaion2->qty);
										$office_part->is_active 	= 5; // Part Ready on TS

										$office_part->save();
									}
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction1->id)->first();
								if(!$delete_inventory) {
									$transaction_delete = Transactions::find($transaction1->id);

									$transaction_delete->delete();
								}

                                $cek_iptn_out =  Transactions::where('id', $ipto->id)->first();
								if($cek_iptn_out){
									$cek_iptn_out->is_iptn_in	=	1;
									$cek_iptn_out->save();
								}

                                $warehouse1 = Warehouses::find($transaction->warehouse_id);
                                $b = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery1 = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                            transaction_id,
                                            item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction1->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                            item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction1->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                // Eksekusi query utama
                                $result1 = DB::table(DB::raw("({$b->toSql()}) AS items"))
                                    ->mergeBindings($b->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery1, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result1->count() > 0){
                                    foreach($result1 as $quotaion3){
                                        $ledger1 = new Ledger_transactions();

										$ledger1->accountNum = $quotaion3->LedgerAccountId;
										$ledger1->transaction_date = $transaction1->date_transaction;
										$ledger1->voucher = $transaction1->code;
										$ledger1->amount = $quotaion3->total;
										$ledger1->currency = "IDR";
										$ledger1->rate = 1;
										$ledger1->total_basic = $quotaion3->total;
										$ledger1->dimention1 = $warehouse1->dimention1;
										$ledger1->dimention2 = $warehouse1->dimention2;
										$ledger1->dimention3 = $warehouse1->dimention3;
										$ledger1->company_id = $transaction1->company_id;
										$ledger1->user_id = $transaction1->user_id;
										$ledger1->transaction_id = $transaction1->id;
										if($transaction1->explanation != null){
											$ledger1->text 			= $transaction1->explanation;
										}

										$ledger1->save();
                                    }
                                }
                            } else if($cs->is_active == 7 && $cek_part->tot_done > 0 && $cs->is_done_ts > 0){
                                $whs_bp = Warehouses::where('flag', 5)->where('is_active', 1)->first();

                                $transaction = new Transactions();

                                $transaction->transaction_type     = 24;
								$transaction->user_id              = $user->id;
								$transaction->location_id          = $whs_bp->location_id;
								$transaction->warehouse_id         = $whs_bp->id;
								$transaction->company_id           = $user->company_id;
								$transaction->code                 = Transactions::getNextCodeAdjustment();
								$transaction->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
								$transaction->adjustment_type 	   = 2;
								$transaction->explanation 		   = "CANCEL (Done CS) - Out Bad Part From Temporary Bad Part TS For CS No. ".$cs->code;
								$transaction->is_service 		   = 1;
								$transaction->cs_id				   = $cs->id;

								$mytime = Carbon::now();
								$transaction->date_required 		= $mytime->toDateTimeString();

								$transaction->save();

								$transaction->date_transaction 		= $transaction->created_at;

								$transaction->save();

                                $part = Office_parts::select('office_part.*')
                                        ->where('office_part.cs_id','=',$cs_item->cs_id)
                                        ->where('office_part.is_active','<', 9)
                                        ->get();

                                foreach($part as $quotaion_itemRequest){
                                    $inventory = new Inventorys();

                                    $inventory->transaction_id		    = $transaction->id;
                                    $inventory->company_id              = $user->company_id;
									$inventory->item_code               = $quotaion_itemRequest->part_id;
									$inventory->item_name               = $quotaion_itemRequest->part_name;
									$inventory->qty                     = -($quotaion_itemRequest->qty_in_ts);
									$inventory->item_unit               = $quotaion_itemRequest->part_unit;
									$inventory->item_group              = substr($quotaion_itemRequest->part_id,0,3);
									$cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $whs_bp->id)->first();
									if($cek_price){
										$inventory->price 				= -($cek_price->avg_price);
									}
									$inventory->total_price    		= -($inventory->qty * $inventory->price);
									$inventory->office_part_id 		= $quotaion_itemRequest->office_part_id;

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest->part_id,0,3))
												->where('TransactionType', 24)
												->where('InventAccountType', 1)
												->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest->part_id,0,3))
												->where('TransactionType', 24)
												->where('InventAccountType', 2)
												->first();

									if($cekdebet){
										$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
									}

									if($cekcredit)  {
										$inventory->offsetAccount = $cekcredit->LedgerAccountId;
									}

									$inventory->save();

									$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $whs_bp->id)->first();
									if($cekstock){
									$total_lama = $cekstock->qty-$quotaion_itemRequest->qty_in_ts;
									$total_price = $cekstock->price+($quotaion_itemRequest->qty_in_ts * (-$inventory->price));

									$inventory->currentQty              = $cekstock->qty-$quotaion_itemRequest->qty_in_ts;
									$inventory->currentValue            = $cekstock->price-$inventory->total_price;

									$cekstock->item_code                = $quotaion_itemRequest->part_id;
									$cekstock->item_name                = $quotaion_itemRequest->part_name;
									$cekstock->item_unit                = $quotaion_itemRequest->part_unit;
									$cekstock->location_id              = $whs_bp->location_id;
									$cekstock->warehouse_id             = $whs_bp->id;
									$cekstock->qty                      = $cekstock->qty-$quotaion_itemRequest->qty_in_ts;
									$cekstock->price                    = $cekstock->price-$inventory->total_price;
									if($total_lama == 0){
									$cekstock->avg_price                = 0;
									}else{
										$cekstock->avg_price            = $total_price/$total_lama;
									}
									$cekstock->save();
									}
									$inventory->save();
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();

								if(!$delete_inventory) {
									$transaction_delete = Transactions::find($transaction->id);
									$transaction_delete->delete();
								}

                                if(!$transaction){
                                    return new OfficeCsResource(false, 'Failed', null);
                                }

                                $warehouse = Warehouses::find($transaction->warehouse_id);
                                $w = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery2 = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                               transaction_id,
                                            item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                             item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                    // Eksekusi query utama
                                $result2 = DB::table(DB::raw("({$w->toSql()}) AS items"))
                                    ->mergeBindings($w->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery2, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result2->count() > 0){
                                    foreach($result2 as $quotaions){
                                        $ledger = new Ledger_transactions();

                                        $ledger->accountNum = $quotaions->LedgerAccountId;
										$ledger->transaction_date = $transaction->date_transaction;
										$ledger->voucher = $transaction->code;
										$ledger->amount = $quotaions->total;
										$ledger->currency = "IDR";
										$ledger->rate = 1;
										$ledger->total_basic = $quotaions->total;
										$ledger->dimention1 = $warehouse->dimention1;
										$ledger->dimention2 = $warehouse->dimention2;
										$ledger->dimention3 = $warehouse->dimention3;
										$ledger->company_id = $transaction->company_id;
										$ledger->user_id = $transaction->user_id;
										$ledger->transaction_id = $transaction->id;
										if($transaction->explanation != null){
											$ledger->text           = $transaction->explanation;
										}
										$ledger->save();
                                    }
                                }

                                if($cs_item->warranty == 1){
                                    $cek_whs = Warehouses::where('flag', 1)->where('is_warranty', 1)->first();
                                }else{
                                    $cek_whs = Warehouses::where('flag', 1)->first();
                                }

                                $transaction = new Transactions();

								$transaction->transaction_type       = 6;
								$transaction->user_id 				 = $user->id;
								$transaction->location_id 			 = $cek_whs->location_id;
								$transaction->warehouse_id			 = $cek_whs->warehouse_id;
								$transaction->company_id			 = $user->company_id;
								$transaction->location_to_id 		 = $cs->location_id;
								$transaction->warehouse_receive_id	 = $cs->warehouse_id;
								$transaction->code     				 = Transactions::getNextCodeIPTO();
								$transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
								$transaction->explanation 			 = $cs->code.' (CANCEL (Done CS) - Send part from TS to CS)';
								$transaction->approval 				 = $user->name;
								$transaction->is_service			 = 1;
								$transaction->cs_id			 	 	 = $cs_item->cs_id;

								$transaction->save();

								$transaction->date_transaction = $transaction->created_at;

								$transaction->save();

                                $parts = Office_parts::select('office_part.*')
                                        ->where('office_part.cs_id','=',$cs_item->cs_id)
                                        ->where('office_part.is_active','<', 9)
                                        ->get();

                                foreach($parts as $quotaion){
                                    $inventory = new Inventorys();
                                    $inventory->transaction_id		= $transaction->id;
									$inventory->company_id			= $user->company_id;
									$inventory->item_code			= $quotaion->part_id;
									$inventory->item_name           = $quotaion->part_name;
									$inventory->item_unit           = $quotaion->part_unit;
									$inventory->qty                 = -($quotaion->qty_in_ts);
									$inventory->item_group        	= substr($quotaion->part_id,0,3);
									$inventory->office_part_id		= $quotaion->id;

									$cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
									if($cek_price){
										$inventory->price 			= -($cek_price->avg_price);
									}
									$inventory->total_price    	 	= -($quotaion->qty_in_ts * $inventory->price);

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
												->where('TransactionType', 6)
												->where('InventAccountType', 1)
												->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion->part_id,0,3))
												->where('TransactionType', 6)
												->where('InventAccountType', 2)
												->first();

									if($cekdebet)	{
										$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
									}else{
										$debet = Inventory_postings::where('TransactionType', 6)
													->where('InventAccountType', 1)
													->first();
										$inventory->ledgerAccount = $debet->LedgerAccountId;
									}

									if($cekcredit)	{
										$inventory->offsetAccount = $cekcredit->LedgerAccountId;
									}else{
										$credit = Inventory_postings::where('TransactionType', 6)
													->where('InventAccountType', 2)
													->first();
										$inventory->offsetAccount = $credit->LedgerAccountId;
									}

									$inventory->save();

									$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
									//var_dump($cekstock);
									if($cekstock){
										$total_lama = $cekstock->qty-($quotaion->qty_in_ts);
										$total_price = $cekstock->price-($quotaion->qty_in_ts * $inventory->price);

										$inventory->currentQty		= $cekstock->qty-$quotaion->qty_in_ts;
										$inventory->currentValue	= $cekstock->price-($quotaion->qty_in_ts * $inventory->price);

										$cekstock->item_code		= $quotaion->part_id;
										$cekstock->item_name		= $quotaion->part_name;
										$cekstock->item_unit		= $quotaion->part_unit;;
										$cekstock->location_id		= $user_Location_id;
										$cekstock->warehouse_id	    = $user_Warehouse_id;
										$cekstock->qty				= $cekstock->qty-$quotaion->qty_in_ts;
										if($total_lama == 0){
											$cekstock->avg_price	= 0;
										}else{
											$cekstock->avg_price	= $total_price/$total_lama;
										}
										$cekstock->price			= $cekstock->price-($cekstock->qty * $inventory->price);
										$cekstock->save();
									}

									$inventory->save();

									if($transaction->iptn_id>0){
										if (($quotaion->qty_in_ts !=0) and ($quotaion->qty_in_ts < $quotaion->qty)) {

											$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
											if($price_list3) {
												$price_list3->qty_out				= $price_list3->qty_out+$quotaion->qty_in_ts;
												$price_list3->save();

												$office_part = Office_parts::find($quotaion->id);
												if($office_part){
													$office_part->stock 			= $office_part->stock - ($quotaion->qty_in_ts);
													$office_part->qty_out_cs	 	= $office_part->qty_out_cs + $quotaion->qty_in_ts;
													$office_part->iptn_out_ts_id 	= $inventory->id;

													$office_part->save();
												}

											}

										} else if (($quotaion->qty_in_ts !=0) and ($quotaion->qty_in_ts >= $quotaion->qty)) {

											$price_list3 = Iptn_items::find($quotaion->iptn_item_id);
											if($price_list3) {
												$price_list3->qty_out      		= $price_list3->qty_out+$quotaion->qty_in_ts;
												$price_list3->is_transfer_out   = 1;
												$price_list3->save();

												$office_part = Office_parts::find($quotaion->id);

												if($office_part){
													$office_part->stock 		 = $office_part->stock - ($quotaion->qty_in_ts);
													$office_part->qty_out_cs	 = $office_part->qty_out_cs + $quotaion->qty_in_ts;
													$office_part->iptn_out_ts_id = $inventory->id;
													$office_part->is_active		 = 4; // Transfered from CS

													$office_part->save();
												}
											}
										}

									}


									//--------------------------------------------edit status purchase order----------------------------------------------------------//
									if($quotaion->iptn_id){
										$out = $quotaion->iptn_id;
										$receive_out = Iptns::find($quotaion->iptn_id);
										if($receive_out){
											$query = Iptns::select('iptn.*')
														->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
														->where('iptn.id','=', $out)
														->get();
											$query_data = $query->toArray();
											$jmlh = count ($query_data);
											//var_dump($jmlh);

											$query1 = Iptns::select('iptn.*')
													->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
													->where('iptn.id','=', $out)
													->where('iptn_item.is_transfer_out','=',1)
													->get();
											$query_data1 = $query1->toArray();
											$jmlh2 = count ($query_data1);

											if ($jmlh == $jmlh2){
												$receive_out->is_submitted = 7;
												$receive_out->save();
											} else {
												$receive_out->is_submitted = 6;
												$receive_out->save();
											}
										}
									}
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
                                if(!$delete_inventory) {
                                    $transaction_delete = Transactions::find($transaction->id);
                                    $transaction_delete->delete();
                                }

                                $warehouse1 = Warehouses::find($transaction->warehouse_id);
                                $a = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery1 = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                               transaction_id,
                                            item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                             item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                    // Eksekusi query utama
                                $result1 = DB::table(DB::raw("({$a->toSql()}) AS items"))
                                    ->mergeBindings($a->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery1, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result1->count() > 0){
                                    foreach($result1 as $quotaion1){
                                        $ledger = new Ledger_transactions();

                                        $ledger->accountNum = $quotaion1->LedgerAccountId;
                                        $ledger->transaction_date = $transaction->date_transaction;
                                        $ledger->voucher = $transaction->code;
                                        $ledger->amount = $quotaion1->total;
										$ledger->currency = "IDR";
										$ledger->rate = 1;
										$ledger->total_basic = $quotaion1->total;
										$ledger->dimention1 = $warehouse1->dimention1;
										$ledger->dimention2 = $warehouse1->dimention2;
										$ledger->dimention3 = $warehouse1->dimention3;
										$ledger->company_id = $transaction->company_id;
										$ledger->user_id = $transaction->user_id;
										$ledger->transaction_id = $transaction->id;
										if($transaction->explanation != null){
											$ledger->text 			= $transaction->explanation;
										}

										$ledger->save();
                                    }
                                }

                                $ipto = Transactions::whereId($transaction->id)->first();

                                $transaction1 = new Transactions();

								$transaction1->transaction_type       = 7;
								$transaction1->date_receive			 = date("Y-m-d");
								$transaction1->date_use				 = date("Y-m-d");
								$transaction1->user_id 				 = $user->id;
								$transaction1->location_id 			 = $ipto->location_to_id;
								$transaction1->warehouse_id			 = $ipto->warehouse_receive_id;
								$transaction1->company_id			 = $user->company_id;
								$transaction1->iptn_out_id	 		 = $ipto->id;
								$transaction1->iptn_out_code	 	 = $ipto->code;
								$transaction1->code     			 = Transactions::getNextCodeIPTIN();
								$transaction1->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
								$transaction1->explanation 			 = $cs->code.' (CANCEL (Done CS) - Receive part from Temporary)';
								$transaction1->approval 			 = $user->name;
								$transaction1->is_service			 = 1;
								$transaction1->cs_id			 	 = $cs_item->cs_id;

								$transaction1->save();

								$transaction1->date_transaction = $transaction1->created_at;

								$transaction1->save();

                                $part1 = DB::table('Inventory')
                                    ->join('transaction','transaction.id','=','inventory.transaction_id')
                                    ->where('inventory.transaction_id','=',$transaction->id)
                                    ->where('transaction.is_service','=',1)
                                    ->where('transaction.cs_id','=',$id)
                                    ->get();

                                foreach($part1 as $quotaion2){
                                    $inventory1 = new Inventorys();

                                    $inventory1->transaction_id          = $transaction1->id;
									$inventory1->company_id              = $user->company_id;
									$inventory1->item_code               = $quotaion2->item_code;
									$inventory1->item_name               = $quotaion2->item_name;
									$inventory1->item_unit               = $quotaion2->item_unit;
									$inventory1->qty                     = -($quotaion2->qty);
									$inventory1->price                   = -($quotaion2->price);
									$inventory1->total_price             = ($quotaion2->qty * $quotaion2->price);
									$inventory1->item_group              = substr($quotaion2->item_code,0,3);
									$inventory1->office_part_id			 = $quotaion2->office_part_id;

									$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
														->where('TransactionType', 7)
														->where('InventAccountType', 1)
														->first();

									$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2->item_code,0,3))
																->where('TransactionType', 7)
																->where('InventAccountType', 2)
																->first();

									if($cekdebet)   {
										$inventory1->ledgerAccount = $cekdebet->LedgerAccountId;
									}else{
										$debet = Inventory_postings::where('TransactionType', 7)
																->where('InventAccountType', 1)
																->first();
										$inventory1->ledgerAccount = $debet->LedgerAccountId;
									}

									if($cekcredit)  {
										$inventory1->offsetAccount = $cekcredit->LedgerAccountId;
									}else{
										$credit = Inventory_postings::where('TransactionType', 7)
																->where('InventAccountType', 2)
																->first();

										$inventory1->offsetAccount = $credit->LedgerAccountId;
									}

									$inventory1->save();

									$cekstock = Stocks::where('item_code', $quotaion2->item_code)->where('warehouse_id', $user_Warehouse_id)->first();

									if($cekstock){
									$inventory1->currentQty                 = -($quotaion2->qty);
									$inventory1->currentValue               = $cekstock->price+($quotaion2->qty * $quotaion2->price);
									$cekstock->item_code                    = $quotaion2->item_code;
									$cekstock->item_name                    = $quotaion2->item_name;
									$cekstock->item_unit                    = $quotaion2->item_unit;
									$cekstock->location_id                  = $ipto->location_to_id;
									$cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
									$cekstock->qty                          = $cekstock->qty+(-($quotaion2->qty));
									$cekstock->price                        = $cekstock->price+($quotaion2->qty * $quotaion2->price);
									if($cekstock->qty>0){
									$cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
									}else{
									$cekstock->avg_price                    =0;
									}
									$cekstock->save();
									}else{
									$cekstock = new Stocks;
									$inventory1->currentQty                 = -($quotaion2->qty);
									$inventory1->currentValue               = ($quotaion2->qty * $quotaion2->price);
									$cekstock->item_code                    = $quotaion2->item_code;
									$cekstock->item_name                    = $quotaion2->item_name;
									$cekstock->item_unit                    = $quotaion2->item_unit;
									$cekstock->location_id                  = $ipto->location_to_id;
									$cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
									$cekstock->qty                          = -($quotaion2->qty);
									$cekstock->price                        = ($quotaion2->qty * $quotaion2->price);
									if($cekstock->qty>0){
									$cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
									}else{
									$cekstock->avg_price                    =0;
									}
									$cekstock->save();
									}

									$inventory1->save();

									$iptn_out = Inventorys::where('transaction_id',$id)
												->where('item_code',$quotaion2->item_code)
												->first();

									if($iptn_out) {
										$iptn_out->is_iptn_in       = 1;
										$iptn_out->save();
									}

									$office_part = Office_parts::find($quotaion2->office_part_id);
									if($office_part){
										$office_part->iptn_in_ts_id	= $inventory1->id;
										$office_part->stock_ts 		= $office_part->stock_ts + -($quotaion2->qty);
										$office_part->qty_in_ts		= $office_part->qty_in_ts + -($quotaion2->qty);
										$office_part->is_active 	= 5; // Part Ready on TS

										$office_part->save();
									}
                                }

                                $delete_inventory = Inventorys::where('transaction_id', $transaction1->id)->first();
                                if(!$delete_inventory){
                                    $transaction_delete = Transactions::find($transaction1->id);
                                    $transaction_delete->delete();
                                }

                                $cek_iptn_out = Transactions::where('id', $ipto->id)->first();
                                if($cek_iptn_out){
                                    $cek_iptn_out->is_iptn_in = 1;
                                    $cek_iptn_out->save();
                                }

                                $warehouse2 = Warehouses::find($transaction->warehouse_id);
                                $c = Transactions::select(DB::raw('id, code'))
                                    ->where('id', $transaction->id);

                                // Subquery untuk gabung data inventory
                                $subquery2 = DB::raw("(
                                    SELECT * FROM (
                                        SELECT
                                            ledgerAccount AS LedgerAccountId,
                                               transaction_id,
                                            item_group,
                                            SUM(total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction1->id}
                                        GROUP BY ledgerAccount, transaction_id, item_group

                                        UNION

                                        SELECT
                                            offsetAccount AS LedgerAccountId,
                                            transaction_id,
                                             item_group,
                                            SUM(-total_price) AS total
                                        FROM inventory
                                        WHERE transaction_id = {$transaction1->id}
                                        GROUP BY offsetAccount, transaction_id, item_group
                                    ) AS c
                                ) AS datas");

                                    // Eksekusi query utama
                                $result2 = DB::table(DB::raw("({$c->toSql()}) AS items"))
                                    ->mergeBindings($c->getQuery()) // penting untuk menjaga binding dari $a
                                    ->select(DB::raw('items.*, datas.*'))
                                    ->leftJoin($subquery2, function ($join) {
                                        $join->on('datas.transaction_id', '=', 'items.id');
                                    })
                                    ->where('datas.total', '!=', 0)
                                    ->get();

                                if($result2->count() > 0){
                                    foreach($result2 as $quotaion3){
                                        $ledger1 = new Ledger_transactions();

                                        $ledger1->accountNum = $quotaion3->LedgerAccountId;
                                        $ledger1->transaction_date = $transaction1->date_transaction;
                                        $ledger1->voucher = $transaction1->code;
                                        $ledger1->amount = $quotaion3->total;
                                        $ledger1->currency = "IDR";
                                        $ledger1->rate = 1;
                                        $ledger1->total_basic = $quotaion3->total;
                                        $ledger1->dimention1 = $warehouse2->dimention1;
                                        $ledger1->dimention2 = $warehouse2->dimention2;
                                        $ledger1->dimention3 = $warehouse2->dimention3;
                                        $ledger1->company_id = $transaction1->company_id;
                                        $ledger1->user_id = $transaction1->user_id;
                                        $ledger1->transaction_id = $transaction1->id;
                                        if($transaction1->explanation != null){
                                            $ledger1->text           = $transaction1->explanation;
                                        }
                                        $ledger1->save();
                                    }
                                }
                            }

                            $whs_dest = Warehouses::where('flag', 6)->where('is_active', 1)->first();

                            $transactiona = new Transactions();

                            $transactiona->transaction_type     = 23;
							$transactiona->user_id              = $user->id;
							$transactiona->location_id          = $whs_dest->location_id;
							$transactiona->warehouse_id         = $whs_dest->id;
							$transactiona->company_id           = $user->company_id;
							$transactiona->code                 = Transactions::getNextCodeAdjustment();
							$transactiona->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
							$transactiona->adjustment_type 		= 1;
							$transactiona->explanation 			= "Receive Bad Part & Unit to Whs Destroyed For CS No. ".$cs->code;
							$transactiona->is_service 			 = 1;
							$transactiona->cs_id				 = $cs_item->cs_id;

							$mytime = Carbon::now();
							$transactiona->date_required 		= $mytime->toDateTimeString();

							$transactiona->save();

							$transactiona->date_transaction 	= $transactiona->created_at;

							$transactiona->save();

                            $a = Office_css::selectRaw('office_cs.id')->where('office_cs.id', $cs_item->cs_id);
                            $detailSubquery = DB::raw("(
                                SELECT * FROM (
                                    SELECT
                                        office_cs_item.id,
                                        office_cs_item.cs_id,
                                        office_customer_item.item_code AS part_id,
                                        office_customer_item.item_name AS part_name,
                                        office_customer_item.item_unit AS part_unit,
                                        1 AS qty
                                    FROM office_cs_item
                                    JOIN office_customer_item ON office_customer_item.id = office_cs_item.item_id
                                    WHERE office_cs_item.cs_id = {$cs_item->cs_id}

                                    UNION

                                    SELECT
                                        office_part.id,
                                        office_part.cs_id,
                                        office_part.part_id,
                                        office_part.part_name,
                                        office_part.part_unit,
                                        office_part.qty
                                    FROM office_part
                                    WHERE office_part.cs_id = {$cs_item->cs_id}
                                ) AS b
                            ) AS detail");

                            // Query utama dengan join
                            $details = DB::table(DB::raw("({$a->toSql()}) AS cs"))
                                ->mergeBindings($a->getQuery())
                                ->select(DB::raw('detail.*'))
                                ->join($detailSubquery, function ($join) {
                                    $join->on('cs.id', '=', 'detail.cs_id');
                                })
                                ->get();

                            foreach($details as $quotaionpart){
                                $inventorya = new Inventorys();
                                $inventorya->transaction_id		= $transactiona->id;
                                $inventorya->company_id			= $user->company_id;
                                $inventorya->item_code			= $quotaionpart->part_id;
                                $inventorya->item_name          = $quotaionpart->part_name;
                                $inventorya->item_unit          = $quotaionpart->part_unit;
                                $inventorya->qty                = ($quotaionpart->qty);
                                $inventorya->item_group         = substr($quotaionpart->part_id,0,3);
                                $inventorya->price              = 0;
                                $inventorya->total_price        = ($quotaionpart->qty * $inventorya->price);
                                $inventorya->office_part_id     = $quotaionpart->id;

                                $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaionpart->part_id,0,3))
                                                ->where('TransactionType', 23)
                                                ->where('InventAccountType', 1)
                                                ->first();
                                $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaionpart->part_id,0,3))
                                                ->where('TransactionType', 5)
                                                ->where('InventAccountType', 1)
                                                ->first();

                                if($cekdebet) {
                                    $inventorya->ledgerAccount = $cekdebet->LedgerAccountId;
                                }

                                if($cekcredit) {
                                    $inventorya->offsetAccount = $cekcredit->LedgerAccountId;
                                }

                                $inventorya->save();

                                $cekstock = Stocks::where('item_code', $inventorya->item_code)->where('warehouse_id', $whs_dest->id)->first();
                                if($cekstock) {
                                    $total_lama = $cekstock->qty+$quotaionpart->qty;
									$total_price = $cekstock->price+($quotaionpart->qty * $inventorya->price);

									$inventorya->currentQty          = $cekstock->qty+$quotaionpart->qty;
									$inventorya->currentValue        = $cekstock->price+($quotaionpart->qty * $inventorya->price);

									$cekstock->item_code            = $quotaionpart->part_id;
									$cekstock->item_name            = $quotaionpart->part_name;
									$cekstock->item_unit            = $quotaionpart->part_unit;
									$cekstock->location_id          = $whs_dest->location_id;
									$cekstock->warehouse_id         = $whs_dest->id;
									$cekstock->qty                  = $cekstock->qty+$quotaionpart->qty;
									$cekstock->price                = $cekstock->price+$inventorya->total_price;
									if($total_lama == 0){
										$cekstock->avg_price        = 0;
									}else{
										$cekstock->avg_price        = $total_price/$total_lama;
									}
									$cekstock->save();
                                }else{
                                    $cekstock = new Stocks();
									$inventorya->currentQty          = $quotaionpart->qty;
									$inventorya->currentValue        = $quotaionpart->qty * $inventorya->price;

									$cekstock->item_code            = $quotaionpart->part_id;
									$cekstock->item_name            = $quotaionpart->part_name;
									$cekstock->item_unit            = $quotaionpart->part_unit;
									$cekstock->location_id          = $whs_dest->location_id;
									$cekstock->warehouse_id         = $whs_dest->id;
									$cekstock->qty                  = $quotaionpart->qty;
									$cekstock->price                = $inventorya->total_price;
									$cekstock->avg_price            = ($cekstock->price)/($cekstock->qty);
									$cekstock->save();
                                }

                                $inventorya->save();
                            }

                            $delete_inventory3 = Inventorys::where('transaction_id', $transactiona->id)->first();

							if(!$delete_inventory3) {
								$transaction_delete = Transactions::find($transactiona->id);
								$transaction_delete->delete();
							}

                            if(!$transactiona) {
                                return new OfficeCsResource(false, 'transaction adjustment missing', null);
                            }

                            $warehousea = Warehouses::find($transaction->warehouse_id);
                            $e= Transactions::select(DB::raw('id, code'))
                                ->where('id', $transaction->id);

                            // Subquery untuk gabung data inventory
                            $subquerya = DB::raw("(
                                SELECT * FROM (
                                    SELECT
                                        ledgerAccount AS LedgerAccountId,
                                            transaction_id,
                                        item_group,
                                        SUM(total_price) AS total
                                    FROM inventory
                                    WHERE transaction_id = {$transactiona->id}
                                    GROUP BY ledgerAccount, transaction_id, item_group

                                    UNION

                                    SELECT
                                        offsetAccount AS LedgerAccountId,
                                        transaction_id,
                                            item_group,
                                        SUM(-total_price) AS total
                                    FROM inventory
                                    WHERE transaction_id = {$transactiona->id}
                                    GROUP BY offsetAccount, transaction_id, item_group
                                ) AS c
                            ) AS datas");

                            // Eksekusi query utama
                            $resulta = DB::table(DB::raw("({$e->toSql()}) AS items"))
                                ->mergeBindings($e->getQuery()) // penting untuk menjaga binding dari $a
                                ->select(DB::raw('items.*, datas.*'))
                                ->leftJoin($subquerya, function ($join) {
                                    $join->on('datas.transaction_id', '=', 'items.id');
                                })
                                ->where('datas.total', '!=', 0)
                                ->get();

                            if($resulta->count() > 0){
                                foreach($resulta as $quotaion5){
                                    $ledger3 = new Ledger_transactions();

                                    $ledger3->accountNum = $quotaion5->Ledger3ccountId;
                                    $ledger3->transaction_date = $transactiona->date_transaction;
                                    $ledger3->voucher = $transactiona->code;
                                    $ledger3->amount = $quotaion5->total;
                                    $ledger3->currency = "IDR";
                                    $ledger3->rate = 1;
                                    $ledger3->total_basic = $quotaion5->total;
                                    $ledger3->dimention1 = $warehousea->dimention1;
                                    $ledger3->dimention2 = $warehousea->dimention2;
                                    $ledger3->dimention3 = $warehousea->dimention3;
                                    $ledger3->company_id = $transactiona->company_id;
                                    $ledger3->user_id = $transactiona->user_id;
                                    $ledger3->transaction_id = $transactiona->id;
                                    if($transactiona->explanation != null){
                                        $ledger3->text = $transactiona->explanation;
                                    }
                                    $ledger3->save();
                                }
                            }
                        }
                    }
                }else{
                    $cs_item->cost = 0;
                }

                $cs_item->cancel_note = $requests['cancel_note'];
                $cs_item->time_done = date("Y-m-d H:i:s");
                $cs_item->user_done = $user->id;

                if($cs->is_ppn > 0){
                    $paid = $cs_item->cost + (($cs_item->cost * $cs->ppn_percen) / 100);
                }else{
                    $paid = $cs_item->cost;
                }

                $update_office_cs = Office_css::whereId($cs_item->cs_id)->first();
                if($update_office_cs){
                    $update_office_cs->cancel_type = $cancel_type;
                    $update_office_cs->cancel_note = $requests['cancel_note'];
                    $update_office_cs->cancel_date = date("Y-m-d H:i:s");
                    $update_office_cs->paid_user = $user->id;
                    $update_office_cs->is_active = 10; // Done CS
                    $update_office_cs->paid = $paid;
                    $update_office_cs->save();
                }

                if($cs_item->req_part > 0){
                    $update_office_part = Office_parts::where('cs_id', $cs_item->cs_id)
                        ->update(['is_active' => 9]);
                }
            }
            $cs_item->approval_cust = $requests['approval_cust'];
			$cs_item->save();

			if($cs_item->approval_cust == 2){
                $cs_item->date_approval_cust = date("Y-m-d H:i:s");
                $cs_item->save();

                $cs_item->is_active = 4;
                if(isset($requests['dp'])){
                    $cs->is_dp = 1;
                    $cs->dp = $requests['dp'];
                    $cs->dp_payment_type = $requests['dp_payment_type'];
                }
                $cs->save();
			}
        }

        if($cs_item){
            return new OfficeCsResource(true, 'success', $cs_item);
        }

        return new OfficeCsResource(false, 'failed', null);
    }

    public function processInvoiceDP($id, Request $request)
    {
        $requests = $request->all();
        $user = auth()->guard('api')->user();
        $user_company_id = $user->company_id;
        $user_id = $user->id;
        $user_name = $user->name;
        $user_Location_id = $user->location_id;
        $user_Warehouse_id = $user->warehouse_id;

        $cs = Office_css::find($id);
		$cs_item = Office_cs_items::select()->where('cs_id', $id)->first();

		if($cs_item){
			if($cs->is_dp>0){
				//-------------------------------------create cust_free_invoice----------------------------------------------------
				$cust_free_invoice = new Cust_free_invoices();
				$cs = Office_css::find($cs_item->cs_id);
				$customer = Customers::find($cs->customer_id);
				$cek_ppn = Item_sales_taxs::select()->where('tipe',1)->where('percen',$cs->ppn_percen)->where('item_sales_tax','LIKE','%PPN%')->first();

				$cust_free_invoice->user_id			= $user_id;
				$cust_free_invoice->location_id		= $user_Location_id;
				$cust_free_invoice->customer_id		= $cs->customer_id;
				$cust_free_invoice->cs_id			= $id;
				$cust_free_invoice->accountNum		= $customer->accountNum;
				$cust_free_invoice->name			= $customer->name;
				$cust_free_invoice->type			= 1;
				$cust_free_invoice->date_invoice	= date("Y-m-d");
				$cust_free_invoice->company_id		= $user_company_id;

				//can be null
				$cust_free_invoice->currency = $customer->currencyCode;
				$cust_free_invoice->description = "Pembayaran uang muka (DP) customer service No.".$cs->code;
				$cust_free_invoice->sales_tax_group_id = 0;
				$cust_free_invoice->is_posting = 1;

				$mytime = Carbon::now();
				$cust_free_invoice->date_posting = $mytime->toDateTimeString();

				$cust_free_invoice->save();

				$x = substr($cust_free_invoice->date_invoice, 2,2);
				$y = substr($cust_free_invoice->date_invoice, 5,2);
				$z = $x.$y;

				if($cs->dp_payment_type == 3){
					$cust_free_invoice->code              = Cust_free_invoices::getNextCounterId($user_company_id, $z);
				}else{
					$cust_free_invoice->code              = Cust_free_invoices::getNextCounterKwt($user_company_id, $z);
				}


				$cust_free_invoice->save();

				if(!$cust_free_invoice) {
					return new OfficeCsResource(false, 'cust free invoice missing', null);
				}


				//-------------------------------------save cust_free_invoice_item--------------------------------------------------
				//$cs_item = Office_cs_items::find($cs->id);
				//save penjualan
				$cust_invoice_item = New Cust_free_invoice_items();

				$cust_invoice_item->cust_free_invoice_id = $cust_free_invoice->id;

				$cust_invoice_item->id_account  		= 352;
				$cust_invoice_item->account  			= "12005000";
				$cust_invoice_item->account_name 		= "Uang Muka";
				$cust_invoice_item->description 		= $cust_free_invoice->description;
				$cust_invoice_item->amount  			= $cs->dp;
				$cust_invoice_item->exchange_rate  		= 1;
				$cust_invoice_item->sales_tax_group_id  = 0;
				$cust_invoice_item->item_sales_tax_id  	= 0;
				$cust_invoice_item->company_code  		= $customer->location_head;
				$cust_invoice_item->department_code 	= $customer->location_department;
				$cust_invoice_item->location_code  		= $customer->location_code;

				$cust_invoice_item->save();

				$cust_invoice_item->total_amount  = $cust_invoice_item->exchange_rate*$cust_invoice_item->amount;

				$cust_invoice_item->save();

				$total = 0;
				$total1 = 0;
				$total = $total + round(($cust_invoice_item->amount),2);
				$total1 = $total1 + 0 ;
				$rate = 1;

				//---------------------------------------------create salesinvoice-------------------------------------------------------------
				$salesinvoice = new Salesinvoices();

				// can not be null
				$salesinvoice->cust_free_invoice_id             = $cust_free_invoice->id;
				$salesinvoice->user_id               			= $user_id;
				$salesinvoice->approval_ap               		= $user_name;
				$salesinvoice->code             				= $cust_free_invoice->code;
				$salesinvoice->date             				= $cust_free_invoice->date_invoice;
				$salesinvoice->tempo             				= $cust_free_invoice->date_invoice;
				$salesinvoice->customer_id             			= $cust_free_invoice->customer_id;
				$salesinvoice->company_id             			= $cust_free_invoice->company_id;
				$salesinvoice->dp_payment_type             		= $cs->dp_payment_type;
				$salesinvoice->cs_id							= $id;
				if($cust_free_invoice->description != null){
					$salesinvoice->inv_number    				= $cust_free_invoice->description;
				}
				$salesinvoice->grand_total             			= round($total,2);
				$salesinvoice->total_ppn 						= round($total1,2);
				$salesinvoice->rate_valas 						= $rate;
				$salesinvoice->total_real 						= $salesinvoice->rate_valas * ($salesinvoice->grand_total+$salesinvoice->total_ppn);
				$salesinvoice->save();

				//update cs
				$cs_update = Office_css::find($cs_item->cs_id);
				if($cs_update){
					$cs_update->inv_dp 		= $salesinvoice->id;
					$cs_update->date_inv_dp = date("Y-m-d h:i:s");

					$cs_update->save();
				}

				//-------------------------------------create Ledger Transaction-----------------------------------------------------------------

				$query1 = Cust_free_invoice_items::select('cust_free_invoice_item.*')
						->where('cust_free_invoice_id','=', $cust_free_invoice->id)
						->get();

				$query_data1 = $query1->toArray();
				$jml = count ($query_data1);
				//var_dump($jml);
				if($jml > 0){

					$customer = Customers::find($cs->customer_id);
					$salesinvoice1 = Salesinvoices::selectRaw('salesinvoice.*, sum(grand_total-discon+total_ppn) as total')->where('cust_free_invoice_id','=',$salesinvoice->cust_free_invoice_id)->get();
					$query_data2 = $salesinvoice1->toArray();
					$data_sales = (array) $query_data2[0];
					if($customer){
						// var_dump('a');
						$ledger = new Ledger_transactions();

						$ledger->accountNum = $customer->LedgerAccountId;
						$ledger->transaction_date = $cust_free_invoice->date_invoice;
						$ledger->voucher = $cust_free_invoice->code;
						$ledger->text = $cust_free_invoice->description;
						if($cust_free_invoice->type==1){
							$ledger->total_basic = $data_sales['total'];
							$ledger->rate = $data_sales['rate_valas'];
							$ledger->amount = $ledger->total_basic * $ledger->rate;
						}else{
							$ledger->total_basic = ($data_sales['total']);
							$ledger->rate = $data_sales['rate_valas'];
							$ledger->amount = $ledger->total_basic * $ledger->rate;
						}
						$ledger->currency = $customer->currencyCode;
						$ledger->company_id = $user_company_id;
						$ledger->user_id = $user_id;
						$ledger->cust_free_invoice_id = $salesinvoice->cust_free_invoice_id;
						$ledger->dimention1 = $customer->location_head;
						$ledger->dimention2 = $customer->location_department;
						$ledger->dimention3 = $customer->location_code;

						$ledger->save();
					}

					$customer2 = Customers::find($cs->customer_id);
					$salesinvoice3 = Salesinvoices::selectRaw('salesinvoice.*, sum(grand_total-discon+total_ppn) as total')->where('cust_free_invoice_id','=',$salesinvoice->cust_free_invoice_id)->get();
					$query_data3 = $salesinvoice3->toArray();
					$data_sales2 = (array) $query_data3[0];
					if($customer2){
						// var_dump('c');
						$ledger = new Ledger_transactions();

						$ledger->accountNum = '11010101';
						$ledger->transaction_date = $cust_free_invoice->date_invoice;
						$ledger->voucher = $cust_free_invoice->code;
						$ledger->text = $cust_free_invoice->description;
						if($cust_free_invoice->type==1){
							$ledger->total_basic = -($data_sales2['total']);
							$ledger->rate = $data_sales2['rate_valas'];
							$ledger->amount = $ledger->total_basic * $ledger->rate;
						}else{
							$ledger->total_basic = -($data_sales2['total']);
							$ledger->rate = $data_sales2['rate_valas'];
							$ledger->amount = $ledger->total_basic * $ledger->rate;
						}
						$ledger->currency = $customer2->currencyCode;
						$ledger->company_id = $user_company_id;
						$ledger->user_id = $user_id;
						$ledger->cust_free_invoice_id = $salesinvoice->cust_free_invoice_id;
						$ledger->dimention1 = $customer2->location_head;
						$ledger->dimention2 = $customer2->location_department;
						$ledger->dimention3 = $customer2->location_code;

						$ledger->save();
					}
				}
			}

            return new OfficeCsResource(true, 'success', $cs_item);
		}

        return new OfficeCsResource(false, 'failed', null);
    }

    public function cekBadPartTS($cs_id)
    {
        $user = auth()->guard('api')->user();
        $cs = Office_css::find($cs_id);
        $cs_item = Office_cs_items::select()->where('cs_id','=',$cs_id)->first();

		if($cs_item->warranty==1){
			$cek_whs = Warehouses::where('flag',1)->where('is_warranty',1)->first();
		}else{
			$cek_whs = Warehouses::where('flag',1)->first();
		}

        $inventory = Inventorys::select(
            'inventory.*',
            'transaction.code as code',
            DB::raw('(select product_code from item where code=inventory.item_code) as product_code'),
            DB::raw('(select name from warehouse where id=transaction.warehouse_id) as whs_name')
            )
            ->join('transaction', 'transaction.id', '=', 'inventory.transaction_id')
            ->join('office_part', 'office_part.id', '=', 'inventory.office_part_id')
            ->where('office_part.cs_id', $cs_id)
            ->where('transaction.is_service', 1)
            ->where('transaction.cs_id', $cs_id)
            ->where('transaction.warehouse_id', $cek_whs->id)
            ->where('transaction.transaction_type', 7)
            ->get();

        if($inventory){
            return new OfficeCsResource(true, 'Success', $inventory);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function cekBadPartWhs($cs_id)
    {
        $user = auth()->guard('api')->user();
        $cs = Office_css::find($cs_id);
		$cs_item = Office_cs_items::select()->where('cs_id','=',$cs_id)->first();

		if($cs_item->warranty==1){
			$cek_whs = Warehouses::where('flag',2)->where('is_warranty',1)->first();
		}else{
			$cek_whs = Warehouses::where('flag',2)->first();
		}

        $inventory = Inventorys::select(
                'inventory.*',
                'transaction.code as code',
                DB::raw('(select product_code from item where code=inventory.item_code) as product_code'),
                DB::raw('(select name from warehouse where id=transaction.id) as whs_name')
            )
            ->join('transaction','transction.id', '=', 'inventory.transaction_id')
            ->join('office_part','office_part.id','=','inventory.office_part_id')
			->where('office_part.cs_id','=',$cs_id)
			->where('transaction.is_service','=',1)
			->where('transaction.cs_id','=',$cs_id)
			->where('transaction.warehouse_id','=',$cek_whs->id)
			->where('transaction.transaction_type', '=', 23)
            ->get();

        if($inventory){
            return new OfficeCsResource(true, 'Success', $inventory);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function transferBadPart(Request $request, $cs_id)
    {
        $user = auth()->guard('api')->user();
        $requests = $request->all();
        $cs = Office_css::find($cs_id);
        if($cs_id){
			$cs->is_active 	   = 7; //change status cs to 'Ready to Pickup'
			$cs->send_bad_part = 1;
			$cs->save();
		}

        $cek_tbp_ts = DB::table('transaction')
                        ->join('warehouse','warehouse.id','=','transaction.warehouse_id')
						->where('transaction.transaction_type','=',23)
						->where('transaction.is_service','=',1)
						->where('transaction.cs_id','=',$cs_id)
						->where('warehouse.flag','=',5)
						->first();

        if($cek_tbp_ts){
            $whs_bp = Warehouses::where('flag', 5)->where('is_active', 1)->first();

            if($requests['warranty']==1){
				$cek_whs = Warehouses::where('flag',2)->where('is_warranty',1)->first();
			}else{
				$cek_whs = Warehouses::where('flag',2)->first();
			}

            $transaction = new Transactions();

			$transaction->transaction_type     = 24;
			$transaction->user_id              = $user->id;
			$transaction->location_id          = $whs_bp->location_id;
			$transaction->warehouse_id         = $whs_bp->id;
			$transaction->company_id           = $user->company_id;
			$transaction->code                 = Transactions::getNextCodeAdjustment();
			$transaction->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
			$transaction->adjustment_type 		= 2;
			$transaction->explanation 			= "Out Bad Part From Temporary Bad Part TS For CS No. ".$cs->code;
			$transaction->is_service 			= 1;
			$transaction->cs_id					= $cs->id;

			$mytime = Carbon::now();
			$transaction->date_required 		= $mytime->toDateTimeString();

			$transaction->save();

			$transaction->date_transaction 		= $transaction->created_at;

			$transaction->save();

            if($requests['bad_parts']){
                $quotaion_itemRequests = $requests['bad_parts'];

                foreach($quotaion_itemRequests as $quotaion_itemRequest){
                    $inventory = new Inventorys;
					$inventory->transaction_id          = $transaction->id;
					$inventory->company_id              = $user->company_id;
					$inventory->item_code               = $quotaion_itemRequest['item_code'];
					$inventory->item_name               = $quotaion_itemRequest['item_name'];
					$inventory->qty                     = -($quotaion_itemRequest['qty_cs']);
					$inventory->item_unit               = $quotaion_itemRequest['item_unit'];
					$inventory->item_group              = substr($quotaion_itemRequest['item_code'],0,3);
					$cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $whs_bp->id)->first();
					if($cek_price){
						$inventory->price 				= -($cek_price->avg_price);
					}
					$inventory->total_price    		= -($inventory->qty * $inventory->price);
					$inventory->office_part_id 		= $quotaion_itemRequest['office_part_id'];

					$cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest['item_code'],0,3))
								->where('TransactionType', 24)
								->where('InventAccountType', 1)
								->first();

					$cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest['item_code'],0,3))
								->where('TransactionType', 24)
								->where('InventAccountType', 2)
								->first();

					if($cekdebet){
						$inventory->ledgerAccount = $cekdebet->LedgerAccountId;
                    }

					if($cekcredit)  {
                        $inventory->offsetAccount = $cekcredit->LedgerAccountId;
					}

					$inventory->save();

					$cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $whs_bp->id)->first();
					if($cekstock){
					$total_lama = $cekstock->qty-$quotaion_itemRequest['qty_cs'];
					$total_price = $cekstock->price+($quotaion_itemRequest['qty_cs'] * (-$inventory->price));

					$inventory->currentQty              = $cekstock->qty-$quotaion_itemRequest['qty_cs'];
					$inventory->currentValue            = $cekstock->price-$quotaion_itemRequest['total_price'];

					$cekstock->item_code                = $quotaion_itemRequest['item_code'];
					$cekstock->item_name                = $quotaion_itemRequest['item_name'];
					$cekstock->item_unit                = $quotaion_itemRequest['item_unit'];
					$cekstock->location_id              = $whs_bp->location_id;
					$cekstock->warehouse_id             = $whs_bp->id;
					$cekstock->qty                      = $cekstock->qty-$quotaion_itemRequest['qty_cs'];
					$cekstock->price                    = $cekstock->price-$quotaion_itemRequest['total_price'];
					if($total_lama == 0){
					$cekstock->avg_price                = 0;
					}else{
						$cekstock->avg_price            = $total_price/$total_lama;
					}
					$cekstock->save();
					}
					$inventory->save();
                }
            }

            $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();

            if(!$delete_inventory){
                $transaction_delete = Transactions::find($transaction->id);
                $transaction_delete->delete();
            }

            if(!$transaction){
                return new OfficeCsResource(false, 'Transaction not found', null);
            }

            $warehouse = Warehouses::find($transaction->warehouse_id);
			$a =  Transactions::select('id, code')->where('id', '=', $transaction->id);
            $debitQuery = DB::table('inventory')
                ->select(
                    DB::raw('ledgerAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    DB::raw('offsetAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $b = DB::table(DB::raw('(' . $a->toSql() . ') items'))
                ->mergeBindings($a->getQuery())
                ->select(DB::raw('items.*, datas.*'))
                ->leftJoin(DB::raw('(' . $unionQuery->toSql() . ') datas'), function ($join) {
                    $join->on('datas.transaction_id', '=', 'items.id');
                })
                ->where('datas.total', '!=', 0)
                ->get();

            if ($b->count() > 0) {
                $b->each(function ($quotaion1) use ($transaction, $warehouse) {
                    $ledger = new Ledger_transactions();
                    $ledger->accountNum = $quotaion1->LedgerAccountId;
                    $ledger->transaction_date = $transaction->date_transaction;
                    $ledger->voucher = $transaction->code;
                    $ledger->amount = $quotaion1->total;
                    $ledger->currency = "IDR";
                    $ledger->rate = 1;
                    $ledger->total_basic = $quotaion1->total;
                    $ledger->dimention1 = $warehouse->dimention1;
                    $ledger->dimention2 = $warehouse->dimention2;
                    $ledger->dimention3 = $warehouse->dimention3;
                    $ledger->company_id = $transaction->company_id;
                    $ledger->user_id = $transaction->user_id;
                    $ledger->transaction_id = $transaction->id;
                    if ($transaction->explanation != null) {
                        $ledger->text = $transaction->explanation;
                    }
                    $ledger->save();
                });
            }

            $transaction2 = new Transactions();

			$transaction2->transaction_type     = 23;
			$transaction2->user_id              = $user->id;
			$transaction2->location_id          = $cek_whs->location_id;
			$transaction2->warehouse_id         = $cek_whs->id;
			$transaction2->company_id           = $user->company_id;
			$transaction2->code                 = Transactions::getNextCodeAdjustment();
			$transaction2->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
			$transaction2->adjustment_type 		= 1;
			$transaction2->explanation 			= "Receive Bad Part in Temporary Bad Part For CS No. ".$cs->code;
			$transaction2->is_service 			= 1;
			$transaction2->cs_id					= $cs->id;

			$mytime = Carbon::now();
			$transaction2->date_required 		= $mytime->toDateTimeString();

			$transaction2->save();

			$transaction2->date_transaction 	= $transaction2->created_at;

			$transaction2->save();

            if($requests['bad_parts']){
                $quotaion_itemRequests2 = $requests['bad_parts'];


            }

            if (!empty($requests['bad_parts'])) {
                foreach ($requests['bad_parts'] as $quotaion_itemRequest2) {
                    $inventory2 = new Inventorys();
                    $inventory2->transaction_id = $transaction2->id;
                    $inventory2->company_id = $user->company_id;
                    $inventory2->item_code = $quotaion_itemRequest2['item_code'];
                    $inventory2->item_name = $quotaion_itemRequest2['item_name'];
                    $inventory2->qty = $quotaion_itemRequest2['qty_cs'];
                    $inventory2->item_unit = $quotaion_itemRequest2['item_unit'];
                    $inventory2->item_group = substr($quotaion_itemRequest2['item_code'], 0, 3);

                    $cek_price = Stocks::where('item_code', $inventory2->item_code)
                        ->where('warehouse_id', $user->warehouse_id)
                        ->first();
                    if ($cek_price) {
                        $inventory2->price = $cek_price->avg_price;
                    }
                    $inventory2->total_price = $quotaion_itemRequest2['qty_cs'] * $inventory2->price;
                    $inventory2->office_part_id = $quotaion_itemRequest2['office_part_id'];

                    $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest2['item_code'], 0, 3))
                        ->where('TransactionType', 23)
                        ->where('InventAccountType', 1)
                        ->first();

                    $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest2['item_code'], 0, 3))
                        ->where('TransactionType', 5)
                        ->where('InventAccountType', 1)
                        ->first();

                    if ($cekdebet) {
                        $inventory2->ledgerAccount = $cekdebet->LedgerAccountId;
                    }
                    if ($cekcredit) {
                        $inventory2->offsetAccount = $cekcredit->LedgerAccountId;
                    }

                    $inventory2->save();

                    $cekstock = Stocks::where('item_code', $inventory2->item_code)
                        ->where('warehouse_id', $cek_whs->id)
                        ->first();

                    if ($cekstock) {
                        $total_lama = $cekstock->qty + $quotaion_itemRequest2['qty_cs'];
                        $total_price = $cekstock->price + ($quotaion_itemRequest2['qty_cs'] * $inventory2->price);

                        $inventory2->currentQty = $cekstock->qty + $quotaion_itemRequest2['qty_cs'];
                        $inventory2->currentValue = $cekstock->price + ($quotaion_itemRequest2['qty_cs'] * $inventory2->price);

                        $cekstock->item_code = $quotaion_itemRequest2['item_code'];
                        $cekstock->item_name = $quotaion_itemRequest2['item_name'];
                        $cekstock->item_unit = $quotaion_itemRequest2['item_unit'];
                        $cekstock->location_id = $cek_whs->location_id;
                        $cekstock->warehouse_id = $cek_whs->id;
                        $cekstock->qty = $cekstock->qty + $quotaion_itemRequest2['qty_cs'];
                        $cekstock->price = $cekstock->price + $inventory2->total_price;
                        $cekstock->avg_price = $total_lama == 0 ? 0 : $total_price / $total_lama;
                        $cekstock->save();
                    } else {
                        $cekstock = new Stocks();
                        $inventory2->currentQty = $quotaion_itemRequest2['qty_cs'];
                        $inventory2->currentValue = $quotaion_itemRequest2['qty_cs'] * $inventory2->price;

                        $cekstock->item_code = $quotaion_itemRequest2['item_code'];
                        $cekstock->item_name = $quotaion_itemRequest2['item_name'];
                        $cekstock->item_unit = $quotaion_itemRequest2['item_unit'];
                        $cekstock->location_id = $cek_whs->location_id;
                        $cekstock->warehouse_id = $cek_whs->id;
                        $cekstock->qty = $quotaion_itemRequest2['qty_cs'];
                        $cekstock->price = $inventory2->total_price;
                        $cekstock->avg_price = $cekstock->qty > 0 ? ($cekstock->price / $cekstock->qty) : 0;
                        $cekstock->save();
                    }
                    $inventory2->save();
                }
            }

            $delete_inventory = Inventorys::where('transaction_id', $transaction2->id)->first();
            if (!$delete_inventory) {
                $transaction_delete = Transactions::find($transaction2->id);
                $transaction_delete->delete();
            }

            if (!$transaction2) {
                return response()->json(['status' => 400, 'message' => 'Transaction not found'], 400);
            }

            // Ledger Transaction
            $warehouse = Warehouses::find($transaction2->warehouse_id);
            $c = Transactions::select('id', 'code')->where('id', $transaction2->id);

            $debitQuery = DB::table('inventory')
                ->select(
                    DB::raw('ledgerAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction2->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    DB::raw('offsetAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction2->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $d = DB::table(DB::raw('(' . $c->toSql() . ') items'))
                ->mergeBindings($c->getQuery())
                ->select(DB::raw('items.*, datas.*'))
                ->leftJoin(DB::raw('(' . $unionQuery->toSql() . ') datas'), function ($join1) {
                    $join1->on('datas.transaction_id', '=', 'items.id');
                })
                ->where('datas.total', '!=', 0)
                ->get();

            if ($d->count() > 0) {
                foreach ($d as $quotaion2) {
                    $ledger1 = new Ledger_transactions();
                    $ledger1->accountNum = $quotaion2->LedgerAccountId;
                    $ledger1->transaction_date = $transaction2->date_transaction;
                    $ledger1->voucher = $transaction2->code;
                    $ledger1->amount = $quotaion2->total;
                    $ledger1->currency = "IDR";
                    $ledger1->rate = 1;
                    $ledger1->total_basic = $quotaion2->total;
                    $ledger1->dimention1 = $warehouse->dimention1;
                    $ledger1->dimention2 = $warehouse->dimention2;
                    $ledger1->dimention3 = $warehouse->dimention3;
                    $ledger1->company_id = $transaction2->company_id;
                    $ledger1->user_id = $transaction2->user_id;
                    $ledger1->transaction_id = $transaction2->id;
                    if ($transaction2->explanation != null) {
                        $ledger1->text = $transaction2->explanation;
                    }
                    $ledger1->save();
                }
            }
        } else {
            if($requests['warranty']==1){
				$cek_whs = Warehouses::where('flag',2)->where('is_warranty',1)->first();
			}else{
				$cek_whs = Warehouses::where('flag',2)->first();
			}

			//----------------Adjust IN In Temporary Bad Part-------------------------
			$transaction = new Transactions();

			$transaction->transaction_type     = 23;
			$transaction->user_id              = $user->id;
			$transaction->location_id          = $cek_whs->location_id;
			$transaction->warehouse_id         = $cek_whs->id;
			$transaction->company_id           = $user->company_id;
			$transaction->code                 = Transactions::getNextCodeAdjustment();
			$transaction->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
			$transaction->adjustment_type 		= 1;
			$transaction->explanation 			= "Receive Bad Part in Temporary Bad Part For CS No. ".$cs->code;
			$transaction->is_service 			= 1;
			$transaction->cs_id					= $cs->id;

			$mytime = Carbon::now();
			$transaction->date_required 		= $mytime->toDateTimeString();

			$transaction->save();

			$transaction->date_transaction 		= $transaction->created_at;

			$transaction->save();

            if (!empty($requests['bad_parts'])) {
                foreach ($requests['bad_parts'] as $quotaion_itemRequest) {
                    $inventory = new Inventorys();
                    $inventory->transaction_id = $transaction->id;
                    $inventory->company_id = $user->company_id;
                    $inventory->item_code = $quotaion_itemRequest['item_code'];
                    $inventory->item_name = $quotaion_itemRequest['item_name'];
                    $inventory->qty = $quotaion_itemRequest['qty_cs'];
                    $inventory->item_unit = $quotaion_itemRequest['item_unit'];
                    $inventory->item_group = substr($quotaion_itemRequest['item_code'], 0, 3);

                    $cek_price = Stocks::where('item_code', $inventory->item_code)
                        ->where('warehouse_id', $user->warehouse_id)
                        ->first();
                    if ($cek_price) {
                        $inventory->price = $cek_price->avg_price;
                    }
                    $inventory->total_price = $quotaion_itemRequest['qty_cs'] * $inventory->price;
                    $inventory->office_part_id = $quotaion_itemRequest['office_part_id'];

                    $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest['item_code'], 0, 3))
                        ->where('TransactionType', 23)
                        ->where('InventAccountType', 1)
                        ->first();

                    $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest['item_code'], 0, 3))
                        ->where('TransactionType', 5)
                        ->where('InventAccountType', 1)
                        ->first();

                    if ($cekdebet) {
                        $inventory->ledgerAccount = $cekdebet->LedgerAccountId;
                    }
                    if ($cekcredit) {
                        $inventory->offsetAccount = $cekcredit->LedgerAccountId;
                    }

                    $inventory->save();

                    $cekstock = Stocks::where('item_code', $inventory->item_code)
                        ->where('warehouse_id', $cek_whs->id)
                        ->first();

                    if ($cekstock) {
                        $total_lama = $cekstock->qty + $quotaion_itemRequest['qty_cs'];
                        $total_price = $cekstock->price + ($quotaion_itemRequest['qty_cs'] * $inventory->price);

                        $inventory->currentQty = $cekstock->qty + $quotaion_itemRequest['qty_cs'];
                        $inventory->currentValue = $cekstock->price + ($quotaion_itemRequest['qty_cs'] * $inventory->price);

                        $cekstock->item_code = $quotaion_itemRequest['item_code'];
                        $cekstock->item_name = $quotaion_itemRequest['item_name'];
                        $cekstock->item_unit = $quotaion_itemRequest['item_unit'];
                        $cekstock->location_id = $cek_whs->location_id;
                        $cekstock->warehouse_id = $cek_whs->id;
                        $cekstock->qty = $cekstock->qty + $quotaion_itemRequest['qty_cs'];
                        $cekstock->price = $cekstock->price + $inventory->total_price;
                        $cekstock->avg_price = $total_lama == 0 ? 0 : $total_price / $total_lama;
                        $cekstock->save();
                    } else {
                        $cekstock = new Stocks();
                        $inventory->currentQty = $quotaion_itemRequest['qty_cs'];
                        $inventory->currentValue = $quotaion_itemRequest['qty_cs'] * $inventory->price;

                        $cekstock->item_code = $quotaion_itemRequest['item_code'];
                        $cekstock->item_name = $quotaion_itemRequest['item_name'];
                        $cekstock->item_unit = $quotaion_itemRequest['item_unit'];
                        $cekstock->location_id = $cek_whs->location_id;
                        $cekstock->warehouse_id = $cek_whs->id;
                        $cekstock->qty = $quotaion_itemRequest['qty_cs'];
                        $cekstock->price = $inventory->total_price;
                        $cekstock->avg_price = $cekstock->qty > 0 ? ($cekstock->price / $cekstock->qty) : 0;
                        $cekstock->save();
                    }
                    $inventory->save();
                }
            }

            $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
            if (!$delete_inventory) {
                $transaction_delete = Transactions::find($transaction->id);
                $transaction_delete->delete();
            }

            if (!$transaction) {
                return response()->json(['status' => 400, 'message' => 'Transaction not found'], 400);
            }

            // Ledger Transaction
            $warehouse = Warehouses::find($transaction->warehouse_id);
            $a = Transactions::selectRaw('id, code')->where('id', $transaction->id);

            $debitQuery = DB::table('inventory')
                ->select(
                    DB::raw('ledgerAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    DB::raw('offsetAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $b = DB::table(DB::raw('(' . $a->toSql() . ') items'))
                ->mergeBindings($a->getQuery())
                ->select(DB::raw('items.*, datas.*'))
                ->leftJoin(DB::raw('(' . $unionQuery->toSql() . ') datas'), function ($join1) {
                    $join1->on('datas.transaction_id', '=', 'items.id');
                })
                ->where('datas.total', '!=', 0)
                ->get();

            if ($b->count() > 0) {
                foreach ($b as $quotaion1) {
                    $ledger = new Ledger_transactions();
                    $ledger->accountNum = $quotaion1->LedgerAccountId;
                    $ledger->transaction_date = $transaction->date_transaction;
                    $ledger->voucher = $transaction->code;
                    $ledger->amount = $quotaion1->total;
                    $ledger->currency = "IDR";
                    $ledger->rate = 1;
                    $ledger->total_basic = $quotaion1->total;
                    $ledger->dimention1 = $warehouse->dimention1;
                    $ledger->dimention2 = $warehouse->dimention2;
                    $ledger->dimention3 = $warehouse->dimention3;
                    $ledger->company_id = $transaction->company_id;
                    $ledger->user_id = $transaction->user_id;
                    $ledger->transaction_id = $transaction->id;
                    if ($transaction->explanation != null) {
                        $ledger->text = $transaction->explanation;
                    }
                    $ledger->save();
                }
            }
        }

        if($transaction){
            return new OfficeCsResource(true, 'Success', $transaction);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function returnBadPart(Request $request, $cs_id)
    {
        $requests = $request->all();
        $user = auth()->guard('api')->user();

        $cs = Office_css::find($cs_id);
		if($cs_id){
			$cs->is_active		= 8; //change status cs to 'Ready to Pickup'
			$cs->is_return_part = $requests['return_part'];
			$cs->save();
		}

		if($requests['return_part'] == 1){ // not returned to customer

			if($requests['warranty']==1){
				$cek_whs = Warehouses::select()->where('flag',2)->where('is_warranty',1)->first();
				$whs_bp  = Warehouses::select()->where('flag',4)->where('is_warranty',1)->first();
			}else{
				$cek_whs = Warehouses::select()->where('flag',2)->first();
				$whs_bp  = Warehouses::select()->where('flag',4)->first();
			}

			//----------------IPTN Out From Temporary Bad Part to Bad Part------------------------
			$transaction = new Transactions();

			$transaction->transaction_type       = 6;
			$transaction->user_id 				 = $user->id;
			$transaction->location_id 			 = $cek_whs->location_id;
			$transaction->warehouse_id			 = $cek_whs->id;
			$transaction->company_id			 = $user->company_id;
			$transaction->location_to_id 		 = $whs_bp->location_id;
			$transaction->warehouse_receive_id	 = $whs_bp->id;
			$transaction->code     				 = Transactions::getNextCodeIPTO();
			$transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
			$transaction->explanation 			 = $cs->code.' (Transfer Spare Part from Temporary to Bad Part to be destroyed)';
			$transaction->approval 				 = $user->name;
			$transaction->is_service			 = 1;
			$transaction->cs_id			 	 	 = $cs_id;

			$transaction->save();

			$transaction->date_transaction = $transaction->created_at;

			$transaction->save();

			//----------------inventory--------------------
			if (!empty($requests['bad_parts'])) {
                foreach ($requests['bad_parts'] as $quotaion_itemRequest) {
                    if ($quotaion_itemRequest['qty_cs'] > 0) {
                        $inventory = new Inventorys();
                        $inventory->transaction_id = $transaction->id;
                        $inventory->company_id = $user->company_id;
                        $inventory->item_name = $quotaion_itemRequest['item_name'];
                        $inventory->item_code = $quotaion_itemRequest['item_code'];
                        $inventory->qty = -($quotaion_itemRequest['qty_cs']);
                        $inventory->item_unit = $quotaion_itemRequest['item_unit'];
                        $inventory->item_group = substr($quotaion_itemRequest['item_code'], 0, 3);

                        $cek_price = Stocks::where('item_code', $inventory->item_code)
                            ->where('warehouse_id', $cek_whs->id)
                            ->first();
                        if ($cek_price) {
                            $inventory->price = -($cek_price->avg_price);
                        }
                        $inventory->total_price = -($quotaion_itemRequest['qty_cs'] * $inventory->price);
                        $inventory->office_part_id = $quotaion_itemRequest['office_part_id'];

                        $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest['item_code'], 0, 3))
                            ->where('TransactionType', 6)
                            ->where('InventAccountType', 1)
                            ->first();

                        $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest['item_code'], 0, 3))
                            ->where('TransactionType', 6)
                            ->where('InventAccountType', 2)
                            ->first();

                        $inventory->ledgerAccount = $cekdebet ? $cekdebet->LedgerAccountId : optional(
                            Inventory_postings::where('TransactionType', 6)->where('InventAccountType', 1)->first()
                        )->LedgerAccountId;

                        $inventory->offsetAccount = $cekcredit ? $cekcredit->LedgerAccountId : optional(
                            Inventory_postings::where('TransactionType', 6)->where('InventAccountType', 2)->first()
                        )->LedgerAccountId;

                        $inventory->save();

                        $cekstock = Stocks::where('item_code', $inventory->item_code)
                            ->where('warehouse_id', $cek_whs->id)
                            ->first();

                        if ($cekstock) {
                            $total_lama = $cekstock->qty - $quotaion_itemRequest['qty_cs'];
                            $total_price = $cekstock->price - $inventory->total_price;

                            $inventory->currentQty = $cekstock->qty - $quotaion_itemRequest['qty_cs'];
                            $inventory->currentValue = $cekstock->price - $inventory->total_price;

                            $cekstock->item_code = $quotaion_itemRequest['item_code'];
                            $cekstock->item_name = $quotaion_itemRequest['item_name'];
                            $cekstock->item_unit = $quotaion_itemRequest['item_unit'];
                            $cekstock->location_id = $cek_whs->location_id;
                            $cekstock->warehouse_id = $cek_whs->id;
                            $cekstock->qty = $cekstock->qty - $quotaion_itemRequest['qty_cs'];
                            $cekstock->price = $cekstock->price - $inventory->total_price;
                            $cekstock->avg_price = $total_lama == 0 ? 0 : $total_price / $total_lama;
                            $cekstock->save();
                        }

                        $inventory->save();
                    }
                }
            }

            //-------------------------------Ledger Transaction------------------------------------//
            $warehouse = Warehouses::find($transaction->warehouse_id);
            $a = Transactions::selectRaw('id, code')->where('id', $transaction->id);

            $debitQuery = DB::table('inventory')
                ->select(
                    DB::raw('ledgerAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    DB::raw('offsetAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $b = DB::table(DB::raw('(' . $a->toSql() . ') items'))
                ->mergeBindings($a->getQuery())
                ->select(DB::raw('items.*, datas.*'))
                ->leftJoin(DB::raw('(' . $unionQuery->toSql() . ') datas'), function ($join) {
                    $join->on('datas.transaction_id', '=', 'items.id');
                })
                ->where('datas.total', '!=', 0)
                ->get();

            if ($b->count() > 0) {
                foreach ($b as $quotaion1) {
                    $ledger = new Ledger_transactions();
                    $ledger->accountNum = $quotaion1->LedgerAccountId;
                    $ledger->transaction_date = $transaction->date_transaction;
                    $ledger->voucher = $transaction->code;
                    $ledger->amount = $quotaion1->total;
                    $ledger->currency = "IDR";
                    $ledger->rate = 1;
                    $ledger->total_basic = $quotaion1->total;
                    $ledger->dimention1 = $warehouse->dimention1;
                    $ledger->dimention2 = $warehouse->dimention2;
                    $ledger->dimention3 = $warehouse->dimention3;
                    $ledger->company_id = $transaction->company_id;
                    $ledger->user_id = $transaction->user_id;
                    $ledger->transaction_id = $transaction->id;
                    if ($transaction->explanation != null) {
                        $ledger->text = $transaction->explanation;
                    }
                    $ledger->save();
                }
            }
		}

        if($requests['return_part'] == 2){ // returned to customer

			if($requests['warranty']==1){
				$cek_whs = Warehouses::where('flag',2)->where('is_warranty',1)->first();
			}else{
				$cek_whs = Warehouses::where('flag',2)->first();
			}

			//----------------Adjust Out From Temporary Bad Part-------------------------
			$transaction2 = new Transactions();

			$transaction2->transaction_type     = 24;
			$transaction2->user_id              = $user->id;
			$transaction2->location_id          = $cek_whs->location_id;;
			$transaction2->warehouse_id         = $cek_whs->id;;
			$transaction2->company_id           = $user->company_id;
			$transaction2->code                 = Transactions::getNextCodeAdjustment();
			$transaction2->barcode              = Transactions::getNextCounterAdjustmentBarcodeId();
			$transaction2->adjustment_type 		= 2;
			$transaction2->explanation 			= $cs->code.' (Return Bad Part to Customer)';
			$transaction2->is_service 			= 1;
			$transaction2->cs_id	 			= $cs->id;

			$mytime = Carbon::now();
			$transaction2->date_required 		= $mytime->toDateTimeString();

			$transaction2->save();

			$transaction2->date_transaction 	= $transaction2->created_at;

			$transaction2->save();

			if($requests['bad_parts']) {
				foreach ($requests['bad_parts'] as $quotaion_itemRequest2) {
                    if ($quotaion_itemRequest2['qty_cs'] > 0) {
                        $inventory2 = new Inventorys();
                        $inventory2->transaction_id = $transaction->id;
                        $inventory2->company_id = $user->company_id;
                        $inventory2->item_name = $quotaion_itemRequest2['item_name'];
                        $inventory2->item_code = $quotaion_itemRequest2['item_code'];
                        $inventory2->qty = -($quotaion_itemRequest2['qty_cs']);
                        $inventory2->item_unit = $quotaion_itemRequest2['item_unit'];
                        $inventory2->item_group = substr($quotaion_itemRequest2['item_code'], 0, 3);

                        $cek_price = Stocks::where('item_code', $inventory2->item_code)
                            ->where('warehouse_id', $cek_whs->id)
                            ->first();
                        if ($cek_price) {
                            $inventory2->price = -($cek_price->avg_price);
                        }
                        $inventory2->total_price = -($quotaion_itemRequest2['qty_cs'] * $inventory2->price);
                        $inventory2->office_part_id = $quotaion_itemRequest2['office_part_id'];

                        $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest2['item_code'], 0, 3))
                            ->where('TransactionType', 24)
                            ->where('InventAccountType', 1)
                            ->first();

                        $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion_itemRequest2['item_code'], 0, 3))
                            ->where('TransactionType', 24)
                            ->where('InventAccountType', 2)
                            ->first();

                        $inventory2->ledgerAccount = $cekdebet ? $cekdebet->LedgerAccountId : optional(
                            Inventory_postings::where('TransactionType', 24)->where('InventAccountType', 1)->first()
                        )->LedgerAccountId;

                        $inventory2->offsetAccount = $cekcredit ? $cekcredit->LedgerAccountId : optional(
                            Inventory_postings::where('TransactionType', 24)->where('InventAccountType', 2)->first()
                        )->LedgerAccountId;

                        $inventory->save();

                        $cekstock2 = Stocks::where('item_code', $inventory2->item_code)
                            ->where('warehouse_id', $cek_whs->id)
                            ->first();

                        if ($cekstock) {
                            $total_lama = $cekstock2->qty - $quotaion_itemRequest2['qty_cs'];
                            $total_price = $cekstock2->price - ($quotaion_itemRequest2['qty_cs'] * $inventory2->total_price);

                            $inventory2->currentQty = $cekstock2->qty - $quotaion_itemRequest2['qty_cs'];
                            $inventory2->currentValue = $cekstock2->price - $quotaion_itemRequest2['total_price'];

                            $cekstock2->item_code = $quotaion_itemRequest2['item_code'];
                            $cekstock2->item_name = $quotaion_itemRequest2['item_name'];
                            $cekstock2->item_unit = $quotaion_itemRequest2['item_unit'];
                            $cekstock2->location_id = $cek_whs->location_id;
                            $cekstock2->warehouse_id = $cek_whs->id;
                            $cekstock2->qty = $cekstock2->qty - $quotaion_itemRequest2['qty_cs'];
                            $cekstock2->price = $cekstock2->price - ($quotaion_itemRequest2['qty_cs'] * $inventory2->price);
                            $cekstock2->avg_price = $total_lama == 0 ? 0 : $total_price / $total_lama;
                            $cekstock2->save();
                        }

                        $inventory2->save();
                    }
                }
			}

			$delete_inventory2 = Inventorys::where('transaction_id', $transaction2->id)->first();

			if(!$delete_inventory2) {
				$transaction_delete2 = Transactions::find($transaction2->id);
				$transaction_delete2->delete();
			}

			if(!$transaction2) {
				return new OfficeCsResource(false, 'Transaction not found', null);
			}

			//-------------------------------Ledger Transaction------------------------------------//

			$warehouse2 = Warehouses::find($transaction2->warehouse_id);
			$e =  Transactions::select('id, code')->where('id', '=', $transaction2->id);

			$debitQuery = DB::table('inventory')
                ->select(
                    DB::raw('ledgerAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(-total_price) as total')
                )
                ->where('transaction_id', $transaction2->id)
                ->groupBy('ledgerAccount');

            $creditQuery = DB::table('inventory')
                ->select(
                    DB::raw('offsetAccount as LedgerAccountId'),
                    'transaction_id',
                    'item_group',
                    DB::raw('SUM(total_price) as total')
                )
                ->where('transaction_id', $transaction2->id)
                ->groupBy('offsetAccount');

            $unionQuery = $debitQuery->union($creditQuery);

            $f = DB::table(DB::raw('(' . $a->toSql() . ') items'))
                ->mergeBindings($a->getQuery())
                ->select(DB::raw('items.*, datas.*'))
                ->leftJoin(DB::raw('(' . $unionQuery->toSql() . ') datas'), function ($join) {
                    $join->on('datas.transaction_id', '=', 'items.id');
                })
                ->where('datas.total', '!=', 0)
                ->get();

            if ($f->count() > 0) {
                foreach ($f as $quotaion3) {
                    $ledger2 = new Ledger_transactions();
                    $ledger2->accountNum = $quotaion3->LedgerAccountId;
                    $ledger2->transaction_date = $transaction->date_transaction;
                    $ledger2->voucher = $transaction->code;
                    $ledger2->amount = $quotaion3->total;
                    $ledger2->currency = "IDR";
                    $ledger2->rate = 1;
                    $ledger2->total_basic = $quotaion3->total;
                    $ledger2->dimention1 = $warehouse->dimention1;
                    $ledger2->dimention2 = $warehouse->dimention2;
                    $ledger2->dimention3 = $warehouse->dimention3;
                    $ledger2->company_id = $transaction->company_id;
                    $ledger2->user_id = $transaction->user_id;
                    $ledger2->transaction_id = $transaction->id;
                    if ($transaction->explanation != null) {
                        $ledger2->text = $transaction->explanation;
                    }
                    $ledger2->save();
                }
            }

		}

        if($transaction || $transaction2){
            return new OfficeCsResource(true, 'Success', [$transaction, $transaction2]);
        }

        return new OfficeCsResource(false, 'failed', null);
    }

    public function sendReminderwithCsId($cs_id)
    {
        $user = auth()->guard('api')->user();

        $cs = Office_css::select(
                'office_cs.id',
                'office_cs.code',
                'office_cs.date_create',
                'office_cs.warehouse_id',
                DB::raw('(select name from warehouse where id=office_cs.warehouse_id) as location'),
                'office_cs.customer_id',
                'customer.name',
                'customer.phone',
                'office_cs.cek_tat',
                'office_cs.count_send',
                DB::raw('IF(office_cs_item.warranty > 0, "Yes", "No") as warranty'),
                DB::raw('(select item_name from office_customer_item where id=office_cs_item.item_id) as unit_name'),
                DB::raw('(select serial_number from office_customer_item where id=office_cs_item.item_id) as serial_number'),
                'office_cs_item.time_done_ts',
                DB::raw('DATEDIFF(NOW(), office_cs_item.time_done_ts) as tat')
            )
            ->join('office_cs_item', 'office_cs_item.cs_id', '=', 'office_cs.id')
            ->join('customer', 'customer.id', '=', 'office_cs.customer_id')
            ->where('office_cs.id', $cs_id)
            ->whereIn('office_cs.is_active', [7,8])
            ->first();

        $qontak_auth = Auth_token_qontak_whatsapps::where("is_active", 1)->first();

        $office_cs = Office_css::where('id', $cs->id)->first();

        if ($office_cs) {
            $now = now();
            $reminderSent = false;
            $reminderNo = null;

            if ($cs->tat == 3) {
                $office_cs->count_send = 1;
                $office_cs->reminder_1 = $now;
                $reminderSent = true;
                $reminderNo = 1;
            } elseif ($cs->tat == 7) {
                $office_cs->count_send = 2;
                $office_cs->reminder_2 = $now;
                $reminderSent = true;
                $reminderNo = 2;
            } elseif ($cs->tat == 14) {
                $office_cs->count_send = 3;
                $office_cs->reminder_3 = $now;
                $reminderSent = true;
                $reminderNo = 3;
            } elseif ($cs->tat == 30) {
                $office_cs->count_send = 4;
                $office_cs->reminder_4 = $now;
                $reminderSent = true;
                $reminderNo = 4;
            } elseif ($cs->tat == 60) {
                $office_cs->count_send = 5;
                $office_cs->reminder_5 = $now;
                $reminderSent = true;
                $reminderNo = 5;
            } elseif ($cs->tat == 90) {
                $office_cs->count_send = 6;
                $office_cs->reminder_6 = $now;
                $reminderSent = true;
                $reminderNo = 6;
            }

            if ($reminderSent) {
                if ($cs->cek_tat == 0) {
                    $office_cs->cek_tat = 1;
                }
                $office_cs->save();

                $template_id = in_array($reminderNo, [5,6]) ? '8932ad76-688b-4573-a220-5357b02c8486' : '2893ea68-f740-47cb-a5cc-282f7d66bf66';

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                        'to_number' => $cs->phone,
                        'to_name' => $cs->name,
                        'message_template_id' => $template_id,
                        'channel_integration_id' => 'd84787ce-41a7-4c5b-a992-e080f89008c9',
                        'language' => [
                            'code' => 'id'
                        ],
                        'parameters' => [
                            'body' => [
                                [
                                    'key' => '1',
                                    'value' => 'tanggal',
                                    'value_text' => date("d/m/Y")
                                ],
                                [
                                    'key' => '2',
                                    'value' => 'nama',
                                    'value_text' => $cs->name
                                ],
                                [
                                    'key' => '3',
                                    'value' => 'no_service',
                                    'value_text' => $cs->code
                                ],
                                [
                                    'key' => '4',
                                    'value' => 'tat',
                                    'value_text' => $cs->tat
                                ],
                            ]
                        ]
                    ]),
                    CURLOPT_HTTPHEADER => [
                        "Authorization: {$qontak_auth->authorization_type} {$qontak_auth->authorization_value}",
                        "Content-Type: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                $monitoring_cron_reminder = new Monitoring_cron_reminders();
                if ($err) {
                    $monitoring_cron_reminder->description = "cURL Error #:" . $err;
                    $monitoring_cron_reminder->status = 'Gagal engirim: ' . $err;
                } else {
                    $monitoring_cron_reminder->description = "Success : $response";
                    $monitoring_cron_reminder->status = 'Berhasil dikirim';
                }
                $monitoring_cron_reminder->name = $cs->name;
                $monitoring_cron_reminder->no_service = $cs->code;
                $monitoring_cron_reminder->phone = $cs->phone;
                $monitoring_cron_reminder->location = $cs->location;
                $monitoring_cron_reminder->tat = 'Informasi Lebih Dari : ' . $cs->tat;
                $monitoring_cron_reminder->save();
            }
        }

        if($reminderSent){
            return new OfficeCsResource(true, 'Success', null);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function saveStatusStock(Request $request, $id, $dex)
    {
        $requests = $request->all();
        $user = auth()->guard('api')->user();
        // dd($request->officeParts);
        $officeParts = $request->officeParts;

        if ($officeParts) {
            $partData = (array) $officeParts[$dex];

            $officePart = Office_parts::find($id);

            if ($officePart) {
                if (isset($partData['status_stock'])) {
                    $officePart->status_stock = $partData['status_stock'];
                }
                $officePart->save();
            }
            return new OfficeCsResource(true, 'Success', $officeParts);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function historyItem($id, $item_id)
    {
        $user = auth()->guard('api')->user();

        $data = DB::table('office_cs_item')
            ->select(
                'office_cs_item.*',
                'office_cs.code as cs_code',
                'office_customer_item.*',
                'office_cost_item.cost as cost',
                'office_technician.name as technician_name',
                'office_cs_item.is_active as is_active',
                DB::raw('DATE_FORMAT(office_cs_item.created_at, "%d/%m/%Y") as date_input')
            )
            ->join('office_cs', 'office_cs_item.cs_id', '=', 'office_cs.id')
            ->leftJoin('office_customer_item', 'office_cs_item.item_id', '=', 'office_customer_item.id')
            ->leftJoin('office_cost_item', 'office_customer_item.item_code', '=', 'office_cost_item.item_code')
            ->leftJoin('office_technician', 'office_cs_item.technician_id', '=', 'office_technician.id')
            ->where('office_cs_item.item_id','=',$item_id)
            ->get();

        if($data){
            return new OfficeCsResource(true, 'Success', $data);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function officeCsItemTypes($id, $status, $cs_id, $type, $item_code)
    {
        $user = auth()->guard('api')->user();
        $date_now = date("Y-m-d");

        if($type == 'RESET'){
            $cs_item = Office_cs_items::find($id);

            if($cs_item->warranty == 0){
                $cek_reset = DB::table('office_reset_item')
                            ->join('office_reset', 'office_reset.id', '=', 'office_reset_item.reset_id')
                            ->where('office_reset_item.item_code', $item_code)
                            ->where('office_reset.is_active', 1)
                            ->where('office_reset.is_submitted', 4)
                            ->where('office_reset.periode_from', '<=', $date_now)
                            ->where('office_reset.periode_to', '=>', $date_now)
                            ->first();

                if($cek_reset){
                    $cs_item->cost = $cek_reset->price;
                }
            }else{
                $cs_item->cost = 0;
            }
            $cs_item->is_active = $status;
            $cs_item->type = $type;

            if($status == 1){
                $cs_item->time_process = date("Y-m-d H:i:s");
                $cs_item->user_process = $user->id;
                $cs = Office_css::find($cs_id);
                $cs->is_active = 2;
                $cs->save();
            }else if ($status == 2){
                $cs_item->time_done = date("Y-m-d H:i:s");
                $cs_item->user_done = $user->id;
            }

            $cs_item->save();
        } else {
            $cs_item = Office_cs_items::find($id);
            $cs_item->is_active = $status;
            $cs_item->type = $type;

            if($status == 1){
                $cs_item->time_process = date("Y-m-d H:i:s");
                $cs_item->user_process = $user->id;
                $cs = Office_css::find($cs_id);
                $cs->is_active = 2;
                $cs->save();
            }else if($status == 2){
                $cs_item->time_done = date("Y-m-d H:i:s");
                $cs_item->user_done = $user->id;
            }

            $cs_item->save();
        }

        if($cs_item){
            return new OfficeCsResource(true, 'Success', $cs_item);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function officeWorkParts($item_code, $cs_item_id)
    {
        $user = auth()->guard('api')->user();
        $date_now = date("Y-m-d H:i:s");
        $ppn = Item_sales_taxs::where('tipe', 1)->where('ppn_active', 1)->first();
        $cs_item = Office_cs_items::whereId($cs_item_id)->first();
        $cs = Office_css::whereId($cs_item->cs_id)->first();

        $csItemQuery = DB::table('office_cs_item')
            ->selectRaw('
                office_cs_item.id as cs_item_id,
                office_cs_item.cs_id,
                office_cs_item.item_id,
                office_customer_item.item_code as cust_item_code,
                office_customer_item.warranty,
                office_customer_item.date_warranty,
                (select is_active from office_cs where id=office_cs_item.cs_id) as active_cs
            ')
            ->join('office_customer_item', 'office_customer_item.id', '=', 'office_cs_item.item_id')
            ->where('office_cs_item.id', $cs_item_id);

            // dd($csItemQuery->get());

        $priceListItemSub = DB::table('office_price_list_item')
            ->selectRaw('
                office_price_list_item.id,
                office_price_list_item.price_list_id,
                office_price_list_item.item_code,
                office_price_list_item.item_name,
                office_price_list_item.item_unit,
                office_price_list_item.product_code,
                office_price_list_item.category_office,
                office_price_list_item.price_spi,
                IF(? > 0, (office_price_list_item.price + ((office_price_list_item.price * ?)/100)), office_price_list_item.price) as price,
                office_price_list.unit_item,
                office_part_item.part_for,
                office_part_item.product_code as part_item_product_code
            ', [
                $cs->is_berikat,
                $ppn->percen
            ])
            ->join('office_price_list', 'office_price_list.id', '=', 'office_price_list_item.price_list_id')
            ->join('office_part_item', function($join) {
                $join->on('office_part_item.product_code', '=', 'office_price_list_item.product_code')
                    ->on('office_part_item.part_for', '=', 'office_price_list.unit_item');
            })
            ->where('office_price_list.is_active', 1)
            ->where('office_price_list.is_submitted', 4)
            ->where('office_price_list.warehouse_id', $user->warehouse_id)
            ->where('office_price_list.unit_item', $item_code)
            ->whereRaw('? between periode_from and periode_to', [$date_now])
            ->orderByDesc('office_price_list_item.updated_at')
            ->limit(1000000);

        // dd($priceListItemSub->get());

        $priceListItemGrouped = DB::query()->fromSub(function($query) use ($priceListItemSub) {
            $query->fromSub($priceListItemSub, 'pli')
                ->groupBy('pli.item_code', 'pli.item_unit');
        }, 'price_list_item');

        $stockInnerSub = DB::table('transaction')
            ->selectRaw('
                inventory.item_code,
                inventory.item_name,
                inventory.item_unit,
                ROUND(SUM(qty),2) as quantity,
                ROUND(SUM(total_price),2) as total,
                transaction.warehouse_id,
                transaction.date_transaction as Tanggal
            ')
            ->join('inventory', 'transaction.id', '=', 'inventory.transaction_id')
            ->whereNotIn('transaction.transaction_type', [14, 15])
            ->where('transaction.warehouse_id', $user->warehouse_id)
            ->where('transaction.company_id', $user->company_id)
            ->whereRaw('date(transaction.date_transaction) > "2017-08-31"')
            ->groupBy('inventory.item_code');

        $stockGrouped = DB::query()
            ->fromSub($stockInnerSub, 'b')
            ->selectRaw('b.*, ROUND(SUM(b.quantity),2) as StockAkhir, ROUND(SUM(b.total),2) as SaldoAkhir')
            ->groupBy('b.item_code')
            ->orderBy('b.item_code', 'asc');

        // 4. Query utama
        $result = DB::query()
            ->fromSub($csItemQuery, 'cs_item')
            ->selectRaw('cs_item.*, price_list_item.*, ? as warehouse_ts_id, (select name from warehouse where id = ?) as warehouse_name, stock.StockAkhir as stock', [
                $user->warehouse_id,
                $user->warehouse_id
            ])
            ->joinSub($priceListItemGrouped, 'price_list_item', function($join) {
                $join->on('cs_item.cust_item_code', '=', 'price_list_item.unit_item');
            })
            ->leftJoinSub($stockGrouped, 'stock', function($join) {
                $join->on('price_list_item.item_code', '=', 'stock.item_code');
            });

        $search = request()->search;

        if ($search) {
            $result = $result
                ->where(function($query) use ($search) {
                    $query->where('cs_item.cust_item_code', 'like', "%$search%")
                        ->orWhere('cs_item.cs_id', 'like', "%$search%")
                        ->orWhere('cs_item.item_id', 'like', "%$search%")
                        ->orWhere('price_list_item.item_code', 'like', "%$search%")
                        ->orWhere('price_list_item.item_name', 'like', "%$search%")
                        ->orWhere('price_list_item.product_code', 'like', "%$search%")
                        ->orWhere('price_list_item.category_office', 'like', "%$search%")
                        ->orWhere('stock.item_code', 'like', "%$search%")
                        ->orWhere('stock.item_name', 'like', "%$search%")
                        ->orWhereRaw('(SELECT name FROM warehouse WHERE id = ?) LIKE ?', [$user->warehouse_id, "%$search%"]);
                });
        }

        $data = $result->get();

        if($data){
            return new OfficeCsResource(true, "success", $data);
        }

        return new OfficeCsResource(false, "failed", null);
    }

    public function officeWorkConsumables($cs_item_id, $item_id)
    {
        $user = auth()->guard('api')->user();
        $user_Warehouse_id = $user->warehouse_id;
        $date_now = date("Y-m-d");

        $consQuery = DB::table('office_consumable')
            ->select('office_consumable.*')
            ->where('office_consumable.is_active', 1)
            ->where('office_consumable.is_submitted', 4)
            ->whereRaw('? between periode_from and periode_to', [$date_now]);

        $consItemInner = DB::table('office_consumable_item')
            ->selectRaw('
                office_consumable_item.id as cons_item_id,
                office_consumable_item.consumable_id,
                office_consumable_item.item_code,
                office_consumable_item.item_name,
                office_consumable_item.item_unit,
                office_consumable_item.product_code,
                office_consumable_item.price,
                office_consumable_item.type_item_office,
                ? as warehouse_id,
                (select name from warehouse where warehouse.id = ? limit 1) as warehouse_name,
                (select category_office from item where item.id = office_consumable_item.item_code limit 1) as category_office
            ', [$user_Warehouse_id, $user_Warehouse_id])
            ->orderByDesc('office_consumable_item.updated_at')
            ->limit(1000000);

        $consItemGrouped = DB::query()->fromSub(function($query) use ($consItemInner) {
            $query->fromSub($consItemInner, 'ofi')
                ->groupBy('ofi.item_code', 'ofi.item_unit');
        }, 'cons_item');

        $stockInner = DB::table('transaction')
            ->selectRaw('
                inventory.item_code,
                inventory.item_name,
                inventory.item_unit,
                ROUND(SUM(qty),2) as quantity,
                ROUND(SUM(total_price),2) as total,
                transaction.warehouse_id,
                transaction.date_transaction as Tanggal
            ')
            ->join('inventory', 'transaction.id', '=', 'inventory.transaction_id')
            ->whereNotIn('transaction.transaction_type', [14, 15])
            ->where('transaction.warehouse_id', $user_Warehouse_id)
            ->where('transaction.company_id', $user->company_id)
            ->whereRaw('date(transaction.date_transaction) > "2017-08-31"')
            ->groupBy('inventory.item_code');

        $stockGrouped = DB::query()
            ->fromSub($stockInner, 'b')
            ->selectRaw('b.*, ROUND(SUM(b.quantity),2) as StockAkhir, ROUND(SUM(b.total),2) as SaldoAkhir')
            ->groupBy('b.item_code')
            ->orderBy('b.item_code', 'asc');

        $result = DB::query()
            ->fromSub($consQuery, 'cons')
            ->selectRaw('cons.*, cons_item.*, ? as cs_item_id, ? as item_id, stock.StockAkhir as stock', [
                $cs_item_id,
                $item_id
            ])
            ->joinSub($consItemGrouped, 'cons_item', function($join) {
                $join->on('cons.id', '=', 'cons_item.consumable_id');
            })
            ->leftJoinSub($stockGrouped, 'stock', function($join) {
                $join->on('cons_item.item_code', '=', 'stock.item_code')
                    ->on('cons_item.warehouse_id', '=', 'stock.warehouse_id');
            });

        $search = request()->search;
        if ($search) {
            $result->where(function($q) use ($search) {
                $q->where('cons_item.item_name', 'like', "%$search%")
                ->orWhere('cons_item.item_code', 'like', "%$search%")
                ->orWhere('cons_item.warehouse_name', 'like', "%$search%")
                ->orWhere('cons_item.category_office', 'like', "%$search%");
            });
        }

        $data = $result->get();

        if($data){
            return new OfficeCsResource(true, 'Success', $data);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function saveNoteCsItem(Request $request, $cs_id)
    {
        $requests = $request->all();
        $cs_item = Office_cs_items::where('cs_id', $cs_id)->first();

        if($cs_item){
            if(isset($requests['problem']))
				$cs_item->problem 	=  $requests['problem'];

			if(isset($requests['note_cs']))
				$cs_item->note_cs 	=  $requests['note_cs'];

			if(isset($requests['note_ts']))
				$cs_item->note_ts 	=  $requests['note_ts'];

			$cs_item->save();

            return new OfficeCsResource(true, 'Success', $cs_item);
        }

        return new OfficeCsResource(false, 'Failed', null);
    }

    public function hideRequestPart(Request $request, $id)
    {
        $requests = $request->all();
        $cs_item = Office_parts::find($id);

        if($cs_item){
            $cs_item->set_req = $requests['set_req'];
            $cs_item->save();
        }
    }

    public function processPartsEin(Request $request, $id, $type)
    {
        $requests = $request->all();
        $user = auth()->guard('api')->user();
        $user_Warehouse_id = $user->warehouse_id;
        $user_Location_id = $user->location_id;

        if($type==1){ // CS Request to Warehouse
            $cs_item = Office_cs_items::where('cs_id','=',$id)->first();
            if($cs_item){
                $cs_item->req_part_to_ein = 1;
                $cs_item->save();

                $cs = Office_css::find($id);
                $cs->is_req_to_ein = 1;
                $cs->save();

                $cust_item = Office_customer_items::find($cs_item->item_id);

                //cek spare part
                $spare_part = DB::table('office_part')
                                ->join('item','item.code','=','office_part.part_id')
                                ->where('office_part.cs_id',$id)
                                ->where('office_part.qty_iptn_out',0)
                                ->get();
                $cek_parts = $spare_part->toArray();
                $cek_part = count ($cek_parts);

                if($cek_part > 0){
                    //create request iptn to whs (spare part)

                    $part = Office_parts::select('office_part.*')
                                ->join('item','item.code','=','office_part.part_id')
                                ->where('office_part.cs_id', '=', $id)
                                ->where('office_part.is_active','<', 9)
                                ->get();
                    $parts = $part->toArray();
                    $tot = count ($parts);

                    for ($i=0; $i < $tot ; $i++) {
                        $quotaion = (array) $parts[$i];

                        $office_part = Office_parts::find($quotaion['id']);
                        if($office_part){
                            $office_part->is_active_wrt = 1;
                            $office_part->save();
                        }
                    }
                }
            }
        }

        if($type==3){ // Send part to TS
            $cs = Office_css::find($id);
            $cs->is_active = 4;
            $cs->is_part = 1;
            $cs->save();
            //var_dump($cs->code);

            $cek_type = Office_parts::selectRaw('if(office_part.type_item_office <> 3, count(office_part.id), 0) as part,
                                if(office_part.type_item_office = 3, count(office_part.id), 0) as cons')
                        ->where('office_part.cs_id','=',$id)
                        ->where('office_part.is_active','<', 9)
                        // ->where('office_part.type_item_office','=', 3)
                        ->where('office_part.qty_iptn_out','>', 0)
                        ->whereRaw('office_part.qty > office_part.qty_in_ts')
                        ->first();

            if($cek_type->part > 0){
                //--------IPTN OUT (CS->TS)--------------
                $iptn = Iptns::where('is_service',1)->where('cs_id',$id)->first();
                //var_dump($iptn->id);
                //var_dump($iptn->no_ipt);
                $warranty = Warehouses::where('central_warehouse_id',$cs->warehouse_id)->first();

                $transaction = new Transactions();

                $transaction->transaction_type       = 6;
                $transaction->user_id 				 = $user->id;
                $transaction->location_id 			 = $warranty->location_id;
                $transaction->warehouse_id			 = $warranty->id;
                $transaction->company_id			 = $user->company_id;
                $transaction->iptn_id 		 		 = $iptn->id;
                $transaction->no_ipt 		 		 = $iptn->no_ipt;
                $transaction->location_to_id 		 = $iptn->location_id_receipt;
                $transaction->warehouse_receive_id	 = $iptn->warehouse_id_receipt;
                $transaction->code     				 = Transactions::getNextCodeIPTO();
                $transaction->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
                $transaction->explanation 			 = $cs->code.' (Send part from CS to TS)';
                $transaction->approval 				 = $user->name;
                $transaction->is_service			 = 1;
                $transaction->cs_id			 	 	 = $id;

                $transaction->save();

                $transaction->date_transaction = $transaction->created_at;

                $transaction->save();

                //----------------inventory--------------------
                $part = Iptn_items::selectRaw('iptn_item.id as iptn_item_id, iptn_item.iptn_id, iptn_item.office_part_id, office_part.*')
                        ->leftJoin('office_part', function ($join){
                                    $join->on('office_part.iptn_ts_id','=','iptn_item.id')
                                        ->on('office_part.id','=','iptn_item.office_part_id')
                                        ->on('office_part.part_id','=','iptn_item.item_code');
                                    })
                        ->where('office_part.cs_id','=',$id)
                        ->where('office_part.is_active','<', 9)
                        //->where('office_part.iptn_in_cs_id','>', 0)
                        //->where('office_part.iptn_out_ts_id','=', 0)
                        ->where('office_part.type_item_office','<', 3)
                        ->where('office_part.qty_iptn_out','>', 0)
                        ->whereRaw('office_part.qty > office_part.qty_in_ts')
                        ->get();
                $parts = $part->toArray();
                $tot = count ($parts);

                for ($i=0; $i < $tot ; $i++) {
                    $quotaion = (array) $parts[$i];

                    $inventory = new Inventorys();
                    $inventory->transaction_id		= $transaction->id;
                    $inventory->company_id			= $user->company_id;
                    $inventory->item_code			= $quotaion['part_id'];
                    $inventory->item_name           = $quotaion['part_name'];
                    $inventory->item_unit           = $quotaion['part_unit'];
                    $inventory->qty                 = -($quotaion['qty_iptn_out']);
                    $inventory->item_group        	= substr($quotaion['part_id'],0,3);
                    $inventory->office_part_id		= $quotaion['id'];
                    $inventory->rma_number			= $cs->rma_number;

                    $cek_price = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $user_Warehouse_id)->first();
                    if($cek_price){
                        $inventory->price 			= -($cek_price->avg_price);
                    }
                    $inventory->total_price    	 	= -($quotaion['qty_iptn_out'] * $inventory->price);

                    $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion['part_id'],0,3))
                                ->where('TransactionType', 6)
                                ->where('InventAccountType', 1)
                                ->first();

                    $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion['part_id'],0,3))
                                ->where('TransactionType', 6)
                                ->where('InventAccountType', 2)
                                ->first();

                    if($cekdebet)	{
                        $inventory->ledgerAccount = $cekdebet->LedgerAccountId;
                    }else{
                        $debet = Inventory_postings::where('TransactionType', 6)
                                    ->where('InventAccountType', 1)
                                    ->first();
                        $inventory->ledgerAccount = $debet->LedgerAccountId;
                    }

                    if($cekcredit)	{
                        $inventory->offsetAccount = $cekcredit->LedgerAccountId;
                    }else{
                        $credit = Inventory_postings::where('TransactionType', 6)
                                    ->where('InventAccountType', 2)
                                    ->first();
                        $inventory->offsetAccount = $credit->LedgerAccountId;
                    }

                    $inventory->save();

                    $cekstock = Stocks::where('item_code', $inventory->item_code)->where('warehouse_id', $warranty->id)->first();
                    //var_dump($cekstock);
                    if($cekstock){
                        $total_lama = $cekstock->qty-($quotaion['qty_iptn_out']);
                        $total_price = $cekstock->price-($quotaion['qty_iptn_out'] * $inventory->price);

                        $inventory->currentQty		= $cekstock->qty-$quotaion['qty_iptn_out'];
                        $inventory->currentValue	= $cekstock->price-($quotaion['qty_iptn_out'] * $inventory->price);

                        $cekstock->item_code		= $quotaion['part_id'];
                        $cekstock->item_name		= $quotaion['part_name'];
                        $cekstock->item_unit		= $quotaion['part_unit'];;
                        $cekstock->location_id		= $warranty->location_id;
                        $cekstock->warehouse_id	    = $warranty->id;
                        $cekstock->qty				= $cekstock->qty-$quotaion['qty_iptn_out'];
                        if($total_lama == 0){
                            $cekstock->avg_price	= 0;
                        }else{
                            $cekstock->avg_price	= $total_price/$total_lama;
                        }
                        $cekstock->price			= $cekstock->price-($cekstock->qty * $inventory->price);
                        $cekstock->save();
                    }

                    $inventory->save();

                    if($transaction->iptn_id>0){
                        if (($quotaion['qty_iptn_out'] !=0) and ($quotaion['qty_iptn_out'] < $quotaion['qty'])) {

                            $price_list3 = Iptn_items::find($quotaion['iptn_item_id']);
                            if($price_list3) {
                                $price_list3->qty_out				= $price_list3->qty_out+$quotaion['qty_iptn_out'];
                                $price_list3->save();

                                $office_part = Office_parts::find($quotaion['id']);
                                if($office_part){
                                    $office_part->stock 			= $office_part->stock - ($quotaion['qty_iptn_out']);
                                    $office_part->qty_out_cs	 	= $office_part->qty_out_cs + $quotaion['qty_iptn_out'];
                                    $office_part->iptn_out_ts_id 	= $inventory->id;

                                    $office_part->save();
                                }

                            }

                        } else if (($quotaion['qty_iptn_out'] !=0) and ($quotaion['qty_iptn_out'] >= $quotaion['qty'])) {

                            $price_list3 = Iptn_items::find($quotaion['iptn_item_id']);
                            if($price_list3) {
                                $price_list3->qty_out      		= $price_list3->qty_out+$quotaion['qty_iptn_out'];
                                $price_list3->is_transfer_out   = 1;
                                $price_list3->save();

                                $office_part = Office_parts::find($quotaion['id']);

                                if($office_part){
                                    $office_part->stock 		 = $office_part->stock - ($quotaion['qty_iptn_out']);
                                    $office_part->qty_out_cs	 = $office_part->qty_out_cs + $quotaion['qty_iptn_out'];
                                    $office_part->iptn_out_ts_id = $inventory->id;
                                    $office_part->is_active		 = 4; // Transfered from CS

                                    $office_part->save();
                                }
                            }
                        }

                    }


                    //--------------------------------------------edit status purchase order----------------------------------------------------------//
                    if($quotaion['iptn_id']){
                        $out = $quotaion['iptn_id'];
                        $receive_out = Iptns::find($quotaion['iptn_id']);
                        if($receive_out){
                            $query = Iptns::select('iptn.*')
                                        ->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
                                        ->where('iptn.id','=', $out)
                                        ->get();
                            $query_data = $query->toArray();
                            $jmlh = count ($query_data);
                            //var_dump($jmlh);

                            $query1 =Iptns::select('iptn.*')
                                    ->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
                                    ->where('iptn.id','=', $out)
                                    ->where('iptn_item.is_transfer_out','=',1)
                                    ->get();
                            $query_data1 = $query1->toArray();
                            $jmlh2 = count ($query_data1);

                            if ($jmlh == $jmlh2){
                                $receive_out->is_submitted = 7;
                                $receive_out->save();
                            } else {
                                $receive_out->is_submitted = 6;
                                $receive_out->save();
                            }
                        }
                    }
                }

                //---------------------------------------------------end--------------------------------------------------------------------------//

                $delete_inventory = Inventorys::where('transaction_id', $transaction->id)->first();
                if(!$delete_inventory) {
                    $transaction_delete = Transactions::find($transaction->id);
                    $transaction_delete->save();

                    $transaction_delete->delete();
                }

                //-------------------------------Ledger Transaction------------------------------------//
                $warehouse = Warehouses::find($transaction->warehouse_id);
                $a =  Transactions::selectRaw('id, code')
                    ->where('id', '=', $transaction->id);

                $subquery = "
                    (
                        SELECT c.*
                        FROM (
                            SELECT ledgerAccount as LedgerAccountId, transaction_id, item_group, SUM(-total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY ledgerAccount, transaction_id, item_group

                            UNION

                            SELECT offsetAccount as LedgerAccountId, transaction_id, item_group, SUM(total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY offsetAccount, transaction_id, item_group
                        ) as c
                    ) as datas
                ";

                // Gabungkan semua dengan bindings dari subquery A
                $b = DB::table(DB::raw('(' . $a->toSql() . ') as items'))
                    ->mergeBindings($a) // penting agar parameter binding dari $a masuk
                    ->selectRaw('items.*, datas.*')
                    ->leftJoin(DB::raw($subquery), function ($join) {
                        $join->on('datas.transaction_id', '=', 'items.id');
                    })
                    ->where('datas.total', '!=', 0)
                    ->setBindings([$id, $id]) // binding untuk subquery inventory (2x pakai transaction_id)
                    ->get();

                $tot = count ($b);

                if($tot > 0){
                    for ($k=0; $k < $tot ; $k++) {
                        $quotaion1 = (array) $b[$k];

                        $ledger = new Ledger_transactions();

                        $ledger->accountNum = $quotaion1['LedgerAccountId'];
                        $ledger->transaction_date = $transaction->date_transaction;
                        $ledger->voucher = $transaction->code;
                        $ledger->amount = $quotaion1['total'];
                        $ledger->currency = "IDR";
                        $ledger->rate = 1;
                        $ledger->total_basic = $quotaion1['total'];
                        $ledger->dimention1 = $warranty->dimention1;
                        $ledger->dimention2 = $warranty->dimention2;
                        $ledger->dimention3 = $warranty->dimention3;
                        $ledger->company_id = $transaction->company_id;
                        $ledger->user_id = $transaction->user_id;
                        $ledger->transaction_id = $transaction->id;
                        if($transaction->explanation != null){
                            $ledger->text 			= $transaction->explanation;
                        }

                        $ledger->save();

                    }
                }

                //-----IPTN IN (TS)-----------
                //$ipto = Transactions::select()->where('transaction_type',6)->where('is_service',1)->where('cs_id',$id)->where('warehouse_id',$user->warehouse_id)->first();
                $ipto = Transactions::select()->where('id',$transaction->id)->first();

                $transaction1 = new Transactions();

                $transaction1->transaction_type       = 7;
                $transaction1->date_receive			 = date("Y-m-d");
                $transaction1->date_use				 = date("Y-m-d");
                $transaction1->user_id 				 = $user->id;
                $transaction1->location_id 			 = $ipto->location_to_id;
                $transaction1->warehouse_id			 = $ipto->warehouse_receive_id;
                $transaction1->company_id			 = $user->company_id;
                $transaction1->iptn_out_id	 		 = $ipto->id;
                $transaction1->iptn_out_code	 	 = $ipto->code;
                $transaction1->code     			 = Transactions::getNextCodeIPTIN();
                $transaction1->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
                $transaction1->explanation 			 = $cs->code.' (Receive part from CS)';
                $transaction1->approval 			 = $user->name;
                $transaction1->is_service			 = 1;
                $transaction1->cs_id			 	 = $id;

                $transaction1->save();

                $transaction1->date_transaction = $transaction1->created_at;

                $transaction1->save();

                //----------------inventory--------------------
                /* $part1 = Office_parts::select()
                            ->where('cs_id', '=', $id)
                            //->where('iptn_out_ts_id','>', 0)
                            //->where('qty_iptn_in','>', 0)
                            ->where('qty_iptn_out','>', 0)
                            ->where('is_active','<', 9)
                            ->get(); */
                $part1 = DB::table('inventory')
                            ->join('transaction','transaction.id','=','inventory.transaction_id')
                            ->where('inventory.transaction_id', '=', $transaction->id)
                            ->where('transaction.is_service', '=', 1)
                            ->where('transaction.cs_id', '=', $id)
                            ->get();
                $parts1 = $part1->toArray();
                $tot1 = count ($parts1);

                for ($j=0; $j < $tot1 ; $j++) {
                    $quotaion2 = (array) $parts1[$j];

                    //$ipto_detail = Inventorys::select()->where('transaction_id',$ipto->id)->where('item_code',$quotaion2['item_code'])->first();

                    $inventory1 = new Inventorys();
                    $inventory1->transaction_id          = $transaction1->id;
                    $inventory1->company_id              = $user->company_id;
                    $inventory1->item_code               = $quotaion2['item_code'];
                    $inventory1->item_name               = $quotaion2['item_name'];
                    $inventory1->item_unit               = $quotaion2['item_unit'];
                    $inventory1->qty                     = -($quotaion2['qty']);
                    $inventory1->price                   = -($quotaion2['price']);
                    $inventory1->total_price             = ($quotaion2['qty'] * $quotaion2['price']);
                    $inventory1->item_group              = substr($quotaion2['item_code'],0,3);
                    $inventory1->office_part_id			 = $quotaion2['office_part_id'];
                    $inventory1->rma_number				 = $cs->rma_number;

                    $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2['item_code'],0,3))
                                        ->where('TransactionType', 7)
                                        ->where('InventAccountType', 1)
                                        ->first();

                    $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2['item_code'],0,3))
                                                ->where('TransactionType', 7)
                                                ->where('InventAccountType', 2)
                                                ->first();

                    if($cekdebet)   {
                        $inventory1->ledgerAccount = $cekdebet->LedgerAccountId;
                    }else{
                        $debet = Inventory_postings::where('TransactionType', 7)
                                                ->where('InventAccountType', 1)
                                                ->first();
                        $inventory1->ledgerAccount = $debet->LedgerAccountId;
                    }

                    if($cekcredit)  {
                        $inventory1->offsetAccount = $cekcredit->LedgerAccountId;
                    }else{
                        $credit = Inventory_postings::where('TransactionType', 7)
                                                ->where('InventAccountType', 2)
                                                ->first();

                        $inventory1->offsetAccount = $credit->LedgerAccountId;
                    }

                    $inventory1->save();

                    $cekstock = Stocks::where('item_code', $quotaion2['item_code'])->where('warehouse_id', $user_Warehouse_id)->first();

                    if($cekstock){
                    $inventory1->currentQty                 = -($quotaion2['qty']);
                    $inventory1->currentValue               = $cekstock->price+($quotaion2['qty'] * $quotaion2['price']);
                    $cekstock->item_code                    = $quotaion2['item_code'];
                    $cekstock->item_name                    = $quotaion2['item_name'];
                    $cekstock->item_unit                    = $quotaion2['item_unit'];
                    $cekstock->location_id                  = $ipto->location_to_id;
                    $cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
                    $cekstock->qty                          = $cekstock->qty+(-($quotaion2['qty']));
                    $cekstock->price                        = $cekstock->price+($quotaion2['qty'] * $quotaion2['price']);
                    if($cekstock->qty>0){
                    $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                    }else{
                    $cekstock->avg_price                    =0;
                    }
                    $cekstock->save();
                    }else{
                    $cekstock = new Stocks();
                    $inventory1->currentQty                 = -($quotaion2['qty']);
                    $inventory1->currentValue               = ($quotaion2['qty'] * $quotaion2['price']);
                    $cekstock->item_code                    = $quotaion2['item_code'];
                    $cekstock->item_name                    = $quotaion2['item_name'];
                    $cekstock->item_unit                    = $quotaion2['item_unit'];
                    $cekstock->location_id                  = $ipto->location_to_id;
                    $cekstock->warehouse_id                 = $ipto->warehouse_receive_id;
                    $cekstock->qty                          = -($quotaion2['qty']);
                    $cekstock->price                        = ($quotaion2['qty'] * $quotaion2['price']);
                    if($cekstock->qty>0){
                    $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                    }else{
                    $cekstock->avg_price                    =0;
                    }
                    $cekstock->save();
                    }

                    $inventory1->save();

                    $iptn_out = Inventorys::where('transaction_id',$transaction->id)
                                ->where('item_code',$quotaion2['item_code'])
                                ->first();

                    if($iptn_out) {
                        $iptn_out->is_iptn_in       = 1;
                        $iptn_out->save();
                    }

                    $office_part = Office_parts::find($quotaion2['office_part_id']);
                    if($office_part){
                        $office_part->iptn_in_ts_id	= $inventory1->id;
                        $office_part->stock_ts 		= $office_part->stock_ts + -($quotaion2['qty']);
                        $office_part->qty_in_ts		= $office_part->qty_in_ts + -($quotaion2['qty']);
                        $office_part->is_active 	= 5; // Part Ready on TS

                        $office_part->save();
                    }

                }

                $delete_inventory = Inventorys::where('transaction_id', $transaction1->id)->first();
                if(!$delete_inventory) {
                    $transaction_delete = Transactions::find($transaction1->id);
                    $transaction_delete->save();

                    $transaction_delete->delete();
                }

                //----------------------------------------update transaction iptn out---------------------------------------------//

                $cek_iptn_out =  Transactions::where('id', $ipto->id)->first();
                if($cek_iptn_out){
                    $cek_iptn_out->is_iptn_in	=	1;
                    $cek_iptn_out->save();
                }

                //----------------------------------------------------------------------------------------------//

                //-------------------------------Ledger Transaction------------------------------------//
                $warehouse1 = Warehouses::find($transaction1->warehouse_id);
                $c =  Transactions::selectRaw('id, code')
                    ->where('id', '=', $transaction1->id);

                 $subquery1 = "
                    (
                        SELECT c.*
                        FROM (
                            SELECT ledgerAccount as LedgerAccountId, transaction_id, item_group, SUM(total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY ledgerAccount, transaction_id, item_group

                            UNION

                            SELECT offsetAccount as LedgerAccountId, transaction_id, item_group, SUM(-total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY offsetAccount, transaction_id, item_group
                        ) as c
                    ) as datas
                ";

                // Gabungkan semua dengan bindings dari subquery A
                $d = DB::table(DB::raw('(' . $c->toSql() . ') as items'))
                    ->mergeBindings($c) // penting agar parameter binding dari $a masuk
                    ->selectRaw('items.*, datas.*')
                    ->leftJoin(DB::raw($subquery1), function ($join) {
                        $join->on('datas.transaction_id', '=', 'items.id');
                    })
                    ->where('datas.total', '!=', 0)
                    ->setBindings([$id, $id]) // binding untuk subquery inventory (2x pakai transaction_id)
                    ->get();

                $tot2 = count ($d);

                if($tot2 > 0){
                    for ($l=0; $l < $tot2 ; $l++) {
                        $quotaion3 = (array) $d[$l];

                        $ledger1 = new Ledger_transactions();

                        $ledger1->accountNum = $quotaion3['LedgerAccountId'];
                        $ledger1->transaction_date = $transaction1->date_transaction;
                        $ledger1->voucher = $transaction1->code;
                        $ledger1->amount = $quotaion3['total'];
                        $ledger1->currency = "IDR";
                        $ledger1->rate = 1;
                        $ledger1->total_basic = $quotaion3['total'];
                        $ledger1->dimention1 = $warehouse1->dimention1;
                        $ledger1->dimention2 = $warehouse1->dimention2;
                        $ledger1->dimention3 = $warehouse1->dimention3;
                        $ledger1->company_id = $transaction1->company_id;
                        $ledger1->user_id = $transaction1->user_id;
                        $ledger1->transaction_id = $transaction1->id;
                        if($transaction1->explanation != null){
                            $ledger1->text 			= $transaction1->explanation;
                        }

                        $ledger1->save();

                    }
                }
            }

            if($cek_type->cons > 0){
                //--------IPTN OUT CONSUMABLE (CS->TS)--------------
                $iptn = Iptns::select()->where('is_service',1)->where('cs_id',$id)->first();

                $transaction2 = new Transactions();

                $transaction2->transaction_type      = 6;
                $transaction2->user_id 				 = $user->id;
                $transaction2->location_id 			 = $cs->location_id;
                $transaction2->warehouse_id			 = $cs->warehouse_id;
                $transaction2->company_id			 = $user->company_id;
                $transaction2->iptn_id 		 		 = $iptn->id;
                $transaction2->no_ipt 		 		 = $iptn->no_ipt;
                $transaction2->location_to_id 		 = $iptn->location_id_receipt;
                $transaction2->warehouse_receive_id	 = $iptn->warehouse_id_receipt;
                $transaction2->code     			 = Transactions::getNextCodeIPTO();
                $transaction2->barcode     			 = Transactions::getNextCodeIPTNOutBarcode();
                $transaction2->explanation 			 = $cs->code.' (Send consumable from CS to TS)';
                $transaction2->approval 			 = $user->name;
                $transaction2->is_service			 = 1;
                $transaction2->cs_id			 	 = $id;

                $transaction2->save();

                $transaction2->date_transaction = $transaction2->created_at;

                $transaction2->save();

                //----------------inventory--------------------
                $part2 = Iptn_items::selectRaw('iptn_item.id as iptn_item_id, iptn_item.iptn_id, iptn_item.office_part_id, office_part.*')
                        ->leftJoin('office_part', function ($join){
                                    $join->on('office_part.iptn_ts_id','=','iptn_item.id')
                                        ->on('office_part.id','=','iptn_item.office_part_id')
                                        ->on('office_part.part_id','=','iptn_item.item_code');
                                    })
                        ->where('office_part.cs_id','=',$id)
                        ->where('office_part.is_active','<', 9)
                        //->where('office_part.iptn_in_cs_id','>', 0)
                        //->where('office_part.iptn_out_ts_id','=', 0)
                        ->where('office_part.type_item_office','=', 3)
                        ->where('office_part.qty_iptn_out','>', 0)
                        ->whereRaw('office_part.qty > office_part.qty_in_ts')
                        ->get();
                $parts2 = $part2->toArray();
                $tot2 = count ($parts2);

                for ($w=0; $w < $tot2 ; $w++) {
                    $quotaion2 = (array) $parts2[$w];

                    $inventory2 = new Inventorys();
                    $inventory2->transaction_id		= $transaction2->id;
                    $inventory2->company_id			= $user->company_id;
                    $inventory2->item_code			= $quotaion2['part_id'];
                    $inventory2->item_name          = $quotaion2['part_name'];
                    $inventory2->item_unit          = $quotaion2['part_unit'];
                    $inventory2->qty                = -($quotaion2['qty_iptn_out']);
                    $inventory2->item_group        	= substr($quotaion2['part_id'],0,3);
                    $inventory2->office_part_id		= $quotaion2['id'];
                    $inventory2->rma_number			= $cs->rma_number;

                    $cek_price2 = Stocks::where('item_code', $inventory2->item_code)->where('warehouse_id', $cs->warehouse_id)->first();
                    if($cek_price2){
                        $inventory2->price 			= -($cek_price2->avg_price);
                    }
                    $inventory2->total_price    	 	= -($quotaion2['qty_iptn_out'] * $inventory2->price);

                    $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion2['part_id'],0,3))
                                ->where('TransactionType', 6)
                                ->where('InventAccountType', 1)
                                ->first();

                    $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion2['part_id'],0,3))
                                ->where('TransactionType', 6)
                                ->where('InventAccountType', 2)
                                ->first();

                    if($cekdebet)	{
                        $inventory2->ledgerAccount = $cekdebet->LedgerAccountId;
                    }else{
                        $debet = Inventory_postings::where('TransactionType', 6)
                                    ->where('InventAccountType', 1)
                                    ->first();
                        $inventory2->ledgerAccount = $debet->LedgerAccountId;
                    }

                    if($cekcredit)	{
                        $inventory2->offsetAccount = $cekcredit->LedgerAccountId;
                    }else{
                        $credit = Inventory_postings::where('TransactionType', 6)
                                    ->where('InventAccountType', 2)
                                    ->first();
                        $inventory2->offsetAccount = $credit->LedgerAccountId;
                    }

                    $inventory2->save();

                    $cekstock = Stocks::where('item_code', $inventory2->item_code)->where('warehouse_id', $cs->warehouse_id)->first();
                    //var_dump($cekstock);
                    if($cekstock){
                        $total_lama = $cekstock->qty-($quotaion2['qty_iptn_out']);
                        $total_price = $cekstock->price-($quotaion2['qty_iptn_out'] * $inventory2->price);

                        $inventory2->currentQty		= $cekstock->qty-$quotaion2['qty_iptn_out'];
                        $inventory2->currentValue	= $cekstock->price-($quotaion2['qty_iptn_out'] * $inventory2->price);

                        $cekstock->item_code		= $quotaion2['part_id'];
                        $cekstock->item_name		= $quotaion2['part_name'];
                        $cekstock->item_unit		= $quotaion2['part_unit'];;
                        $cekstock->location_id		= $cs->location_id;
                        $cekstock->warehouse_id	    = $cs->warehouse_id;
                        $cekstock->qty				= $cekstock->qty-$quotaion2['qty_iptn_out'];
                        if($total_lama == 0){
                            $cekstock->avg_price	= 0;
                        }else{
                            $cekstock->avg_price	= $total_price/$total_lama;
                        }
                        $cekstock->price			= $cekstock->price-($cekstock->qty * $inventory2->price);
                        $cekstock->save();
                    }

                    $inventory2->save();

                    if($transaction2->iptn_id>0){
                        if (($quotaion2['qty_iptn_out'] !=0) and ($quotaion2['qty_iptn_out'] < $quotaion2['qty'])) {

                            $price_list3 = Iptn_items::find($quotaion2['iptn_item_id']);
                            if($price_list3) {
                                $price_list3->qty_out				= $price_list3->qty_out+$quotaion2['qty_iptn_out'];
                                $price_list3->save();

                                $office_part = Office_parts::find($quotaion2['id']);
                                if($office_part){
                                    $office_part->stock 			= $office_part->stock - ($quotaion2['qty_iptn_out']);
                                    $office_part->qty_out_cs	 	= $office_part->qty_out_cs + $quotaion2['qty_iptn_out'];
                                    $office_part->iptn_out_ts_id 	= $inventory2->id;

                                    $office_part->save();
                                }

                            }

                        } else if (($quotaion2['qty_iptn_out'] !=0) and ($quotaion2['qty_iptn_out'] >= $quotaion2['qty'])) {

                            $price_list3 = Iptn_items::find($quotaion2['iptn_item_id']);
                            if($price_list3) {
                                $price_list3->qty_out      		= $price_list3->qty_out+$quotaion2['qty_iptn_out'];
                                $price_list3->is_transfer_out   = 1;
                                $price_list3->save();

                                $office_part = Office_parts::find($quotaion2['id']);

                                if($office_part){
                                    $office_part->stock 		 = $office_part->stock - ($quotaion2['qty_iptn_out']);
                                    $office_part->qty_out_cs	 = $office_part->qty_out_cs + $quotaion2['qty_iptn_out'];
                                    $office_part->iptn_out_ts_id = $inventory2->id;
                                    $office_part->is_active		 = 4; // Transfered from CS

                                    $office_part->save();
                                }
                            }
                        }

                    }


                    //--------------------------------------------edit status purchase order----------------------------------------------------------//
                    if($quotaion2['iptn_id']){
                        $out = $quotaion2['iptn_id'];
                        $receive_out = Iptns::find($quotaion2['iptn_id']);
                        if($receive_out){
                            $query = Iptns::select('iptn.*')
                                        ->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
                                        ->where('iptn.id','=', $out)
                                        ->get();
                            $query_data = $query->toArray();
                            $jmlh = count ($query_data);
                            //var_dump($jmlh);

                            $query1 =Iptns::select('iptn.*')
                                    ->join('iptn_item','iptn.id','=','iptn_item.iptn_id')
                                    ->where('iptn.id','=', $out)
                                    ->where('iptn_item.is_transfer_out','=',1)
                                    ->get();
                            $query_data1 = $query1->toArray();
                            $jmlh2 = count ($query_data1);

                            if ($jmlh == $jmlh2){
                                $receive_out->is_submitted = 7;
                                $receive_out->save();
                            } else {
                                $receive_out->is_submitted = 6;
                                $receive_out->save();
                            }
                        }
                    }
                }

                //---------------------------------------------------end--------------------------------------------------------------------------//

                $delete_inventory = Inventorys::where('transaction_id', $transaction2->id)->first();
                if(!$delete_inventory) {
                    $transaction_delete = Transactions::find($transaction2->id);
                    $transaction_delete->save();

                    $transaction_delete->delete();
                }

                //-------------------------------Ledger Transaction------------------------------------//
                $warehouse = Warehouses::find($transaction2->warehouse_id);
                $a =  Transactions::selectRaw('id, code')
                    ->where('id', '=', $transaction2->id);

                 $subquery = "
                    (
                        SELECT c.*
                        FROM (
                            SELECT ledgerAccount as LedgerAccountId, transaction_id, item_group, SUM(-total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY ledgerAccount, transaction_id, item_group

                            UNION

                            SELECT offsetAccount as LedgerAccountId, transaction_id, item_group, SUM(total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY offsetAccount, transaction_id, item_group
                        ) as c
                    ) as datas
                ";

                // Gabungkan semua dengan bindings dari subquery A
                $b = DB::table(DB::raw('(' . $a->toSql() . ') as items'))
                    ->mergeBindings($a) // penting agar parameter binding dari $a masuk
                    ->selectRaw('items.*, datas.*')
                    ->leftJoin(DB::raw($subquery), function ($join) {
                        $join->on('datas.transaction_id', '=', 'items.id');
                    })
                    ->where('datas.total', '!=', 0)
                    ->setBindings([$id, $id]) // binding untuk subquery inventory (2x pakai transaction_id)
                    ->get();

                $tot = count ($b);

                if($tot > 0){
                    for ($k=0; $k < $tot ; $k++) {
                        $quotaion2 = (array) $b[$k];

                        $ledger = new Ledger_transactions();

                        $ledger->accountNum = $quotaion2['LedgerAccountId'];
                        $ledger->transaction_date = $transaction2->date_transaction;
                        $ledger->voucher = $transaction2->code;
                        $ledger->amount = $quotaion2['total'];
                        $ledger->currency = "IDR";
                        $ledger->rate = 1;
                        $ledger->total_basic = $quotaion2['total'];
                        $ledger->dimention1 = $warehouse->dimention1;
                        $ledger->dimention2 = $warehouse->dimention2;
                        $ledger->dimention3 = $warehouse->dimention3;
                        $ledger->company_id = $transaction2->company_id;
                        $ledger->user_id = $transaction2->user_id;
                        $ledger->transaction_id = $transaction2->id;
                        if($transaction2->explanation != null){
                            $ledger->text 			= $transaction2->explanation;
                        }

                        $ledger->save();
                    }
                }

                //-----IPTN IN (TS CONSUMABLE)-----------
                $ipto1 = Transactions::select()->where('id',$transaction2->id)->first();

                $transaction3 = new Transactions();

                $transaction3->transaction_type      = 7;
                $transaction3->date_receive			 = date("Y-m-d");
                $transaction3->date_use				 = date("Y-m-d");
                $transaction3->user_id 				 = $user->id;
                $transaction3->location_id 			 = $ipto1->location_to_id;
                $transaction3->warehouse_id			 = $ipto1->warehouse_receive_id;
                $transaction3->company_id			 = $user->company_id;
                $transaction3->iptn_out_id	 		 = $ipto1->id;
                $transaction3->iptn_out_code	 	 = $ipto1->code;
                $transaction3->code     			 = Transactions::getNextCodeIPTIN();
                $transaction3->barcode     			 = Transactions::getNextCodeIPTNInBarcode();
                $transaction3->explanation 			 = $cs->code.' (Receive consumable from CS)';
                $transaction3->approval 			 = $user->name;
                $transaction3->is_service			 = 1;
                $transaction3->cs_id			 	 = $id;

                $transaction3->save();

                $transaction3->date_transaction = $transaction3->created_at;

                $transaction3->save();

                //----------------inventory--------------------
                /* $part1 = Office_parts::select()
                            ->where('cs_id', '=', $id)
                            //->where('iptn_out_ts_id','>', 0)
                            //->where('qty_iptn_in','>', 0)
                            ->where('qty_iptn_out','>', 0)
                            ->where('is_active','<', 9)
                            ->get(); */
                $part2 = DB::table('inventory')
                            ->join('transaction','transaction.id','=','inventory.transaction_id')
                            ->where('inventory.transaction_id', '=', $transaction2->id)
                            ->where('transaction.is_service', '=', 1)
                            ->where('transaction.cs_id', '=', $id)
                            ->get();
                $parts2 = $part2->toArray();
                $tot1 = count ($parts2);

                for ($y=0; $y < $tot1 ; $y++) {
                    $quotaion3 = (array) $parts2[$y];

                    //$ipto_detail = Inventorys::select()->where('transaction_id',$ipto->id)->where('item_code',$quotaion3['item_code'])->first();

                    $inventory3 = new Inventorys();
                    $inventory3->transaction_id          = $transaction3->id;
                    $inventory3->company_id              = $user->company_id;
                    $inventory3->item_code               = $quotaion3['item_code'];
                    $inventory3->item_name               = $quotaion3['item_name'];
                    $inventory3->item_unit               = $quotaion3['item_unit'];
                    $inventory3->qty                     = -($quotaion3['qty']);
                    $inventory3->price                   = -($quotaion3['price']);
                    $inventory3->total_price             = ($quotaion3['qty'] * $quotaion3['price']);
                    $inventory3->item_group              = substr($quotaion3['item_code'],0,3);
                    $inventory3->office_part_id			 = $quotaion3['office_part_id'];
                    $inventory3->rma_number				 = $cs->rma_number;

                    $cekdebet = Inventory_postings::where('ItemRelation', substr($quotaion3['item_code'],0,3))
                                        ->where('TransactionType', 7)
                                        ->where('InventAccountType', 1)
                                        ->first();

                    $cekcredit = Inventory_postings::where('ItemRelation', substr($quotaion3['item_code'],0,3))
                                                ->where('TransactionType', 7)
                                                ->where('InventAccountType', 2)
                                                ->first();

                    if($cekdebet)   {
                        $inventory3->ledgerAccount = $cekdebet->LedgerAccountId;
                    }else{
                        $debet = Inventory_postings::where('TransactionType', 7)
                                                ->where('InventAccountType', 1)
                                                ->first();
                        $inventory3->ledgerAccount = $debet->LedgerAccountId;
                    }

                    if($cekcredit)  {
                        $inventory3->offsetAccount = $cekcredit->LedgerAccountId;
                    }else{
                        $credit = Inventory_postings::where('TransactionType', 7)
                                                ->where('InventAccountType', 2)
                                                ->first();

                        $inventory3->offsetAccount = $credit->LedgerAccountId;
                    }

                    $inventory3->save();

                    $cekstock = Stocks::where('item_code', $quotaion3['item_code'])->where('warehouse_id', $user_Warehouse_id)->first();

                    if($cekstock){
                    $inventory3->currentQty                 = -($quotaion3['qty']);
                    $inventory3->currentValue               = $cekstock->price+($quotaion3['qty'] * $quotaion3['price']);
                    $cekstock->item_code                    = $quotaion3['item_code'];
                    $cekstock->item_name                    = $quotaion3['item_name'];
                    $cekstock->item_unit                    = $quotaion3['item_unit'];
                    $cekstock->location_id                  = $ipto1->location_to_id;
                    $cekstock->warehouse_id                 = $ipto1->warehouse_receive_id;
                    $cekstock->qty                          = $cekstock->qty+(-($quotaion3['qty']));
                    $cekstock->price                        = $cekstock->price+($quotaion3['qty'] * $quotaion3['price']);
                    if($cekstock->qty>0){
                    $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                    }else{
                    $cekstock->avg_price                    =0;
                    }
                    $cekstock->save();
                    }else{
                    $cekstock = new Stocks();
                    $inventory3->currentQty                 = -($quotaion3['qty']);
                    $inventory3->currentValue               = ($quotaion3['qty'] * $quotaion3['price']);
                    $cekstock->item_code                    = $quotaion3['item_code'];
                    $cekstock->item_name                    = $quotaion3['item_name'];
                    $cekstock->item_unit                    = $quotaion3['item_unit'];
                    $cekstock->location_id                  = $ipto1->location_to_id;
                    $cekstock->warehouse_id                 = $ipto1->warehouse_receive_id;
                    $cekstock->qty                          = -($quotaion3['qty']);
                    $cekstock->price                        = ($quotaion3['qty'] * $quotaion3['price']);
                    if($cekstock->qty>0){
                    $cekstock->avg_price                    = ($cekstock->price)/($cekstock->qty);
                    }else{
                    $cekstock->avg_price                    =0;
                    }
                    $cekstock->save();
                    }

                    $inventory3->save();

                    $iptn_out = Inventorys::where('transaction_id',$transaction2->id)
                                ->where('item_code',$quotaion3['item_code'])
                                ->first();

                    if($iptn_out) {
                        $iptn_out->is_iptn_in       = 1;
                        $iptn_out->save();
                    }

                    $office_part = Office_parts::find($quotaion3['office_part_id']);
                    if($office_part){
                        $office_part->iptn_in_ts_id	= $inventory3->id;
                        $office_part->stock_ts 		= $office_part->stock_ts + -($quotaion3['qty']);
                        $office_part->qty_in_ts		= $office_part->qty_in_ts + -($quotaion3['qty']);
                        $office_part->is_active 	= 5; // Part Ready on TS

                        $office_part->save();
                    }

                }

                $delete_inventory = Inventorys::where('transaction_id', $transaction3->id)->first();
                if(!$delete_inventory) {
                    $transaction_delete = Transactions::find($transaction3->id);
                    $transaction_delete->save();

                    $transaction_delete->delete();
                }

                //----------------------------------------update transaction iptn out---------------------------------------------//

                $cek_iptn_out =  Transactions::where('id', $ipto1->id)->first();
                if($cek_iptn_out){
                    $cek_iptn_out->is_iptn_in	=	1;
                    $cek_iptn_out->save();
                }

                //----------------------------------------------------------------------------------------------//

                //-------------------------------Ledger Transaction------------------------------------//
                $warehouse1 = Warehouses::find($transaction3->warehouse_id);
                $c =  Transactions::selectRaw('id, code')
                    ->where('id', '=', $transaction3->id);

                 $subquery1 = "
                    (
                        SELECT c.*
                        FROM (
                            SELECT ledgerAccount as LedgerAccountId, transaction_id, item_group, SUM(-total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY ledgerAccount, transaction_id, item_group

                            UNION

                            SELECT offsetAccount as LedgerAccountId, transaction_id, item_group, SUM(total_price) as total
                            FROM inventory
                            WHERE transaction_id = ?
                            GROUP BY offsetAccount, transaction_id, item_group
                        ) as c
                    ) as datas
                ";

                // Gabungkan semua dengan bindings dari subquery A
                $b = DB::table(DB::raw('(' . $c->toSql() . ') as items'))
                    ->mergeBindings($c) // penting agar parameter binding dari $a masuk
                    ->selectRaw('items.*, datas.*')
                    ->leftJoin(DB::raw($subquery1), function ($join) {
                        $join->on('datas.transaction_id', '=', 'items.id');
                    })
                    ->where('datas.total', '!=', 0)
                    ->setBindings([$id, $id]) // binding untuk subquery inventory (2x pakai transaction_id)
                    ->get();

                $tot2 = count ($d);

                if($tot2 > 0){
                    for ($l=0; $l < $tot2 ; $l++) {
                        $quotaion3 = (array) $d[$l];

                        $ledger1 = new Ledger_transactions();

                        $ledger1->accountNum = $quotaion3['LedgerAccountId'];
                        $ledger1->transaction_date = $transaction3->date_transaction;
                        $ledger1->voucher = $transaction3->code;
                        $ledger1->amount = $quotaion3['total'];
                        $ledger1->currency = "IDR";
                        $ledger1->rate = 1;
                        $ledger1->total_basic = $quotaion3['total'];
                        $ledger1->dimention1 = $warehouse1->dimention1;
                        $ledger1->dimention2 = $warehouse1->dimention2;
                        $ledger1->dimention3 = $warehouse1->dimention3;
                        $ledger1->company_id = $transaction3->company_id;
                        $ledger1->user_id = $transaction3->user_id;
                        $ledger1->transaction_id = $transaction3->id;
                        if($transaction3->explanation != null){
                            $ledger1->text 			= $transaction3->explanation;
                        }

                        $ledger1->save();
                    }
                }
            }
        }
    }

}
