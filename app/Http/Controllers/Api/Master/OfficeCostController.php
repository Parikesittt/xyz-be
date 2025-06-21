<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Office_costs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeCostResource;
use App\Models\Office_cost_items;
use App\Models\Office_cost_onsites;
use App\Models\Office_onsite_areas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class OfficeCostController extends Controller
{


    public function index()
    {
        $office_cost = Office_costs::when(request()->search, function ($office_cost) {
            $office_cost = $office_cost->where('description', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%');
        })->latest()->orderBy('id', 'asc')->paginate(10);

        $office_cost->appends(['search' => request()->search]);

        return new OfficeCostResource(true, 'List data office cost', $office_cost);
    }

    public function store(Request $request)
    {
        $items =$request->office_cost_item;
        $user = auth()->guard('api')->user();
        $office_cost = new Office_costs();
        $office_cost->code = Office_costs::getNextCode();
        $office_cost->user_id = $user->id;
        $office_cost->company_id = $user->company_id;
        $office_cost->user_posting = '';
        $office_cost->periode_from = $request->periode_from ?? 0;
        $office_cost->periode_to = $request->periode_to ?? 0;
        $office_cost->description = $request->description ?? 0;
        $office_cost->cost = $request->cost ?? 0;
        $office_cost->cancel_cost = $request->cancel_cost ?? 0;
        if($request->is_submitted == 4){
            $office_cost->is_submitted = $request->is_submitted;
            $office_cost->posting_date = date("Y-m-d H:i:s");
        }
        $office_cost->is_active = $request->is_active;
        $office_cost->save();

        $items = is_string($request->office_cost_item) ? json_decode($request->office_cost_item, true) : $request->office_cost_item;
        if (!is_array($items)) {
            Log::error('office_cost_item is not an array:', ['data' => $items]);
            return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
        }

            foreach ($items as $item) {
                $office_cost_item = new Office_cost_items();
                $office_cost_item->cost_id = $office_cost->id;
                $office_cost_item->item_name = $item['name'] ?? null;
                $office_cost_item->item_code = $item['code'] ?? null;
                $office_cost_item->item_unit = $item['unit'] ?? null;
                $office_cost_item->product_code = $item['product_code'] ?? null;
                $office_cost_item->category_office = $item['category_office'] ?? null;
                $office_cost_item->cost = $office_cost->cost;
                $office_cost_item->cancel_cost = $office_cost->cancel_cost;
                $office_cost_item->save();
            }

        if($office_cost)
        {
            return new OfficeCostResource(true, 'Data office cost Berhasil Disimpan!', $office_cost);
        }

        return new OfficeCostResource(false, 'Data office cost Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $officeCost = Office_costs::whereId($id)->with('office_cost_items')->first();

        if($officeCost)
        {
            return new OfficeCostResource(true, 'Detail Data officeCost!', $officeCost);
        }

        return new OfficeCostResource(false, 'Detail Data officeCost Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->guard('api')->user();
        $office_cost = Office_costs::whereId($id)->first();
        $office_cost->periode_from = $request->periode_from ?? 0;
        $office_cost->periode_to = $request->periode_to ?? 0;
        $office_cost->description = $request->description ?? 0;
        $office_cost->cost = $request->cost ?? 0;
        $office_cost->cancel_cost = $request->cancel_cost ?? 0;
        if($request->is_submitted == 4){
            $office_cost->is_submitted = $request->is_submitted;
            $office_cost->user_posting = $user->name;
            $office_cost->posting_date = date("Y-m-d H:i:s");
        }
        $office_cost->is_active = $request->is_active;
        $office_cost->save();

        $items = is_string($request->office_cost_item) ? json_decode($request->office_cost_item, true) : $request->office_cost_item;
        if (!is_array($items)) {
            Log::error('office_cost_item is not an array:', ['data' => $items]);
            return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
        }

        foreach ($items as $item) {
            if(!empty($item['id'])){
                $office_cost_item = Office_cost_items::whereId($item['id'])->first();
                $office_cost_item->cost_id = $office_cost->id;
                $office_cost_item->item_name = $item['name'] ?? null;
                $office_cost_item->item_code = $item['code'] ?? null;
                $office_cost_item->item_unit = $item['unit'] ?? null;
                $office_cost_item->product_code = $item['product_code'] ?? null;
                $office_cost_item->category_office = $item['category_office'] ?? null;
                $office_cost_item->cost = $office_cost->cost;
                $office_cost_item->cancel_cost = $office_cost->cancel_cost;
                $office_cost_item->save();
            }else{
                $office_cost_item = new Office_cost_items();
                $office_cost_item->cost_id = $office_cost->id;
                $office_cost_item->item_name = $item['name'] ?? null;
                $office_cost_item->item_code = $item['code'] ?? null;
                $office_cost_item->item_unit = $item['unit'] ?? null;
                $office_cost_item->product_code = $item['product_code'] ?? null;
                $office_cost_item->category_office = $item['category_office'] ?? null;
                $office_cost_item->cost = $office_cost->cost;
                $office_cost_item->cancel_cost = $office_cost->cancel_cost;
                $office_cost_item->save();
            }
        }
        if($office_cost)
        {
            return new OfficeCostResource(true, 'Data office cost Berhasil Diupdate!', $office_cost);
        }

        return new OfficeCostResource(false, 'Data City Gagal Diupdate!', null);
    }

    public function destroy(Office_costs $City)
    {
        if($City->delete())
        {
            return new OfficeCostResource(true, 'Data City Berhasil Dihapus!', null);
        }

        return new OfficeCostResource(false, 'Data City Gagal Dihapus!', null);
    }

    public function all()
    {
        $cities = Office_costs::latest()->get();

        return new OfficeCostResource(true, 'List Data City!', $cities);
    }

    public function cekOfficeCostItem()
    {
        $date_now = date("Y-m-d");

        $costSubquery = DB::table('office_cost')
            ->select('*')
            ->where('is_active', 1)
            ->where('is_submitted', 4)
            ->whereRaw('? between periode_from and periode_to', [$date_now]);

        $costItemSubquery = DB::table('office_cost_item')
            ->select(
                'office_cost_item.*',
                DB::raw('(SELECT itemcategory_id FROM item WHERE item.id = office_cost_item.item_code LIMIT 1) as item_category_id')
            )
            ->orderByDesc('updated_at');

        $data = DB::query()
            ->fromSub($costSubquery, 'cost')
            ->joinSub($costItemSubquery, 'cost_item', function($join) {
                $join->on('cost.id', '=', 'cost_item.cost_id');
            })
            ->select(
                'cost.*',
                'cost_item.*',
                DB::raw('(SELECT name FROM itemcategory WHERE id = cost_item.item_category_id LIMIT 1) as item_category')
            )
            ->when(request()->search, function ($query) {
                $query->where(function($q) {
                    $q->where('cost_item.item_code', 'like', '%'.request()->search.'%')
                    ->orWhere('cost_item.item_name', 'like', '%'.request()->search.'%')
                    ->orWhere('cost_item.product_code', 'like', '%'.request()->search.'%');
                });
            })
            ->orderBy('cost.created_at', 'desc') // <-- Tentukan tabel cost
            ->orderBy('cost.id', 'asc') // <-- Tentukan tabel cost
            ->paginate(10);


        $data->appends(['search' => request()->search]);

        return new OfficeCostResource(true, 'List data office cost', $data);
    }

    public function getCustomerItem($customer_id)
    {
        $date_now = date("Y-m-d");

        $custItemSubquery = DB::table('office_customer_item')
            ->select([
                'id as item_id',
                'customer_id',
                'item_code',
                'item_name',
                'item_unit',
                'product_code',
                'category_office',
                'serial_number',
                'warranty',
                'date_warranty',
                'created_at'
            ])
            ->where('customer_id', $customer_id);

        // Subquery 2: Cost Items
        $costSubquery = DB::table('office_cost')
            ->select([
                'office_cost.*',
                'office_cost_item.cost_id',
                'office_cost_item.item_code',
                'office_cost_item.product_code'
            ])
            ->join('office_cost_item', 'office_cost.id', '=', 'office_cost_item.cost_id')
            ->where('office_cost.is_active', 1)
            ->where('office_cost.is_submitted', 4)
            ->whereRaw('? between office_cost.periode_from and office_cost.periode_to', [$date_now])
            ->orderByDesc('office_cost_item.updated_at')
            ->limit(1000000);

        // Subquery 3: CS Items
        $csItemSubquery = DB::table('office_cs')
            ->select([
                DB::raw('count(office_cs.id) as cs'),
                'office_cs_item.item_id',
                'office_cs_item.warranty'
            ])
            ->join('office_cs_item', 'office_cs.id', '=', 'office_cs_item.cs_id')
            ->where('office_cs.is_active', '<', 9)
            ->groupBy('office_cs_item.item_id');

        // Main Query
        $data = DB::query()
            ->fromSub($custItemSubquery, 'cust_item')
            ->select([
                'cust_item.item_id',
                'cust_item.customer_id',
                'cust_item.item_code',
                'cust_item.item_name',
                'cust_item.item_unit',
                'cust_item.product_code',
                'cust_item.category_office',
                'cust_item.serial_number',
                'cust_item.created_at',
                DB::raw('ANY_VALUE(cust_item.warranty) as warranty'), // Kolom non-agregasi
                'cust_item.date_warranty',
                DB::raw('IF(cust_item.warranty > 0, 0, cost.cost) as cost_service'),
                DB::raw('IF(cs_item.cs > 0, 0, 1) as status_service')
            ])
            ->joinSub($costSubquery, 'cost', function($join) {
                $join->on('cust_item.item_code', '=', 'cost.item_code')
                    ->on('cust_item.product_code', '=', 'cost.product_code');
            })
            ->leftJoinSub($csItemSubquery, 'cs_item', function($join) {
                $join->on('cust_item.item_id', '=', 'cs_item.item_id');
            })
            ->groupBy( // Tambahkan SEMUA kolom non-agregasi
                'cust_item.item_id',
                'cust_item.customer_id',
                'cust_item.item_code',
                'cust_item.item_name',
                'cust_item.item_unit',
                'cust_item.product_code',
                'cust_item.category_office',
                'cust_item.serial_number',
                'cust_item.date_warranty',
                'cust_item.warranty',
                'cost.cost',
                'cs_item.cs'
            )
            ->when(request()->search, function($query) {
                $query->where(function($q) {
                    $q->where('cust_item.item_code', 'like', "%".request()->search."%")
                    ->orWhere('cust_item.product_code', 'like', "%".request()->search."%")
                    ->orWhere('cust_item.serial_number', 'like', "%".request()->search."%");
                });
            })
            ->orderByDesc('cust_item.created_at')
            ->orderBy('cust_item.item_id')
            ->paginate(10);


        $data->appends(['search' => request()->search]);

        return new OfficeCostResource(true, 'List data office cost', $data);
    }

    public function onSiteIndex()
    {
        $user = auth()->guard('api')->user();

        if($user->user_group_id == 1){
            $office_cost_on_site = DB::table('office_cost_onsite')
                                ->select('office_cost_onsite.*', DB::raw('(select name from office_onsite_area where id = office_cost_onsite.area_id) as area_name'))
                                ->when(request()->search, function ($office_cost_on_site) {
                                    $office_cost_on_site = $office_cost_on_site->where('description', 'like', '%' . request()->search . '%')
                                    ->orWhere('code', 'like', '%' . request()->search . '%');
                                })->latest()->orderBy('id', 'asc')->paginate(10);
        }else{
            $office_cost_on_site = DB::table('office_cost_onsite')
                                ->select('office_cost_onsite.*', DB::raw('(select name from office_onsite_area where id = office_cost_onsite.area_id) as area_name'))
                                ->when(request()->search, function ($office_cost_on_site) {
                                    $office_cost_on_site = $office_cost_on_site->where('description', 'like', '%' . request()->search . '%')
                                    ->orWhere('code', 'like', '%' . request()->search . '%');
                                })
                                ->where('office_cost_onsite.is_submitted', 1)
                                ->where('office_cost_onsite.is_active', 1)
                                ->latest()->orderBy('id', 'asc')->paginate(10);
        }

        $office_cost_on_site->appends(['search' => request()->search]);

        return new OfficeCostResource(true, 'List data office cost', $office_cost_on_site);
    }

    public function onSiteShow($id)
    {
        $costOnSite = Office_cost_onsites::whereId($id)->first();

        if($costOnSite){
            return new OfficeCostResource(true, 'Data cost on site', $costOnSite);
        }
    }

    public function onSiteStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area_id'   => 'required',
            'location'  => 'required',
            'price'     => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $costOnSite = new Office_cost_onsites();
        $costOnSite->code = Office_cost_onsites::getNextCode();
        $costOnSite-> area_id = $request->area_id;
        $costOnSite-> location = $request->location;
        $costOnSite-> price = $request->price;
        $costOnSite-> is_submitted = $request->is_submitted ?? 0;
        $costOnSite-> is_active = $request->is_active ?? 0;
        $costOnSite->save();

        if($costOnSite){
            return new OfficeCostResource(true, 'Cost On Site telah dibuat', $costOnSite);
        }

        return new OfficeCostResource(false, 'Cost On Site gagal dibuat', null);
    }

    public function onSiteUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'area_id'   => 'required',
            'location'  => 'required',
            'price'     => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $costOnSite = Office_cost_onsites::whereId($id)->first();
        $costOnSite-> area_id = $request->area_id;
        $costOnSite-> location = $request->location;
        $costOnSite-> price = $request->price;
        $costOnSite-> is_submitted = $request->is_submitted ?? 0;
        $costOnSite-> is_active = $request->is_active ?? 0;
        $costOnSite->save();

        if($costOnSite){
            return new OfficeCostResource(true, 'Cost On Site telah diperbarui', $costOnSite);
        }

        return new OfficeCostResource(false, 'Cost On Site gagal diperbarui', null);
    }

    public function onSiteAll()
    {
        $costOnSite = Office_cost_onsites::latest()->get();

        return new OfficeCostResource(true, 'Data Cost On Site', $costOnSite);
    }

    public function onSiteArea()
    {
        $data = Office_onsite_areas::latest()->get();

        return new OfficeCostResource(true, 'List data cost on site area', $data);
    }
}
