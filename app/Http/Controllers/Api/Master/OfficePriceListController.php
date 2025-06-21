<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Office_price_lists;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficePriceListResource;
use App\Models\Itemcategorys;
use App\Models\Items;
use App\Models\Office_consumable_items;
use App\Models\Office_consumables;
use App\Models\Office_part_items;
use App\Models\Office_price_list_items;
use App\Models\Office_price_list_whss;
use App\Models\Office_reset_items;
use App\Models\Office_resets;
use App\Models\Office_so_price_list_items;
use App\Models\Office_so_price_list_whss;
use App\Models\Office_so_price_lists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OfficePriceListController extends Controller
{
    public function index()
    {
        $price_lists = Office_price_lists::when(request()->search, function ($price_lists) {
            $price_lists = $price_lists->where('description', 'like', '%' . request()->search . '%')
            ->orWhere('unit_name', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%')
            ->orWhere('unit_item', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $price_lists->appends(['search' => request()->search]);

        return new OfficePriceListResource(true, 'List data Facility', $price_lists);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();

        $price_list = new Office_price_lists();
        $price_list->user_id = $user->id;
        $price_list->company_id = $user->company_id;
        $price_list->periode_from = $request->periode_from ?? 0;
        $price_list->periode_to = $request->periode_to ?? 0;
        $price_list->description = $request->description ?? '';
        $price_list->unit_item = $request->unit_item ?? '';
        $price_list->unit_name = $request->unit_name ?? '';
        $price_list->unit_product_no = $request->unit_product_no ?? '';
        $price_list->location_id = $request->location_id ?? '';
        $price_list->warehouse_id = $request->warehouse_id ?? 0;
        $price_list->warehouse_name = $request->warehouse_name ?? 0;
        if($request->is_submitted == 4){
            $price_list->is_submitted = $request->is_submitted;
            $price_list->posting_date = date("Y-m-d H:i:s");
        }else{
            $price_list->is_submitted = 0;
            $price_list->posting_date = null;
        }
        $price_list->is_active = $request->is_active ?? 0;
        $price_list->code = Office_price_lists::getNextCode();
        $price_list->save();

        // $items = is_string($request->price_list_items) ? json_decode($request->price_list_items, true) : $request->price_list_items;
        // if (!is_array($items)) {
        //     Log::error('office_cost_item is not an array:', ['data' => $items]);
        //     return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
        // }

        // foreach ($items as $item) {
        //     if(!empty($item['id'])){
        //         $price_list_item = new Office_price_list_items();
        //         $price_list_item->cost_id = $price_list->id;
        //         $price_list_item->item_name = $item['item_name'];
        //         $price_list_item->item_code = $item['item_code'];
        //         $price_list_item->item_unit = $item['item_unit'];
        //         $price_list_item->product_code = $item['product_code'] ?? null;
        //         $price_list_item->price_spi = $item['price_spi'] ?? null;
        //         $price_list_item->price = $item['price'] ?? null;
        //         $price_list_item->category_office = $item['category_office'] ?? null;
        //         $price_list_item->save();
        //     }
        // }

        // $itemwhs = is_string($request->price_list_whs) ? json_decode($request->price_list_whs, true) : $request->price_list_whs;
        // if (!is_array($itemwhs)) {
        //     Log::error('office_cost_item is not an array:', ['data' => $itemwhs]);
        //     return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
        // }

        // foreach ($itemwhs as $item) {
        //     if(!empty($item['warehouse_id'])){
        //         $price_list_whs = new Office_price_list_whss();
        //         $price_list_whs->cost_id = $price_list->id;
        //         $price_list_whs->warehouse_id = $item['warehouse_id'];
        //         $price_list_whs->save();
        //     }
        // }

        if($price_list)
        {
            return new OfficePriceListResource(true, 'Data price list Berhasil Disimpan!', $price_list);
        }

        return new OfficePriceListResource(false, 'Data price list Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $price_list = Office_price_lists::whereId($id)->first();

        if($price_list)
        {
            return new OfficePriceListResource(true, 'Detail Data price list!', $price_list);
        }

        return new OfficePriceListResource(false, 'Detail Data price list Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $user = auth()->guard('api')->user();

        $price_list = Office_price_lists::whereId($id)->first();
        $price_list->periode_from = $request->periode_from ?? 0;
        $price_list->periode_to = $request->periode_to ?? 0;
        $price_list->description = $request->description ?? '';
        $price_list->unit_item = $request->unit_item ?? '';
        $price_list->unit_name = $request->unit_name ?? '';
        $price_list->unit_product_no = $request->unit_product_no ?? '';
        $price_list->location_id = $request->location_id ?? '';
        $price_list->warehouse_id = $request->warehouse_id ?? 0;
        $price_list->warehouse_name = $request->warehouse_name ?? 0;
        if($request->is_submitted == 4){
            $price_list->is_submitted = $request->is_submitted;
            $price_list->user_posting = $user->name;
            $price_list->posting_date = date("Y-m-d H:i:s");
        }else{
            $price_list->is_submitted = 0;
            $price_list->posting_date = null;
        }
        $price_list->is_active = $request->is_active ?? 0;
        $price_list->save();

        if($request->price_list_items){
            $items = is_string($request->price_list_items) ? json_decode($request->price_list_items, true) : $request->price_list_items;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['unit'])){
                    $price_list_item = new Office_price_list_items();
                    $price_list_item->price_list_id = $price_list->id;
                    $price_list_item->item_name = $item['name'];
                    $price_list_item->item_code = $item['code'];
                    $price_list_item->item_unit = $item['unit'];
                    $price_list_item->product_code = $item['product_code'] ?? null;
                    $price_list_item->price_spi = $item['price_spi'] ?? null;
                    $price_list_item->price = $item['price'] ?? null;
                    $price_list_item->category_office = $item['category_office'] ?? null;
                    $price_list_item->save();
                    // dd($price_list_item);
                }
            }
        }

        // $itemwhs = is_string($request->price_list_whs) ? json_decode($request->price_list_whs, true) : $request->price_list_whs;
        // if (!is_array($itemwhs)) {
        //     Log::error('office_cost_item is not an array:', ['data' => $itemwhs]);
        //     return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
        // }

        // foreach ($itemwhs as $item) {
        //     if(!empty($item['warehouse_id'])){
        //         $price_list_whs = new Office_price_list_whss();
        //         $price_list_whs->cost_id = $price_list->id;
        //         $price_list_whs->warehouse_id = $item['warehouse_id'];
        //         $price_list_whs->save();
        //     }
        // }


        if($price_list)
        {
            return new OfficePriceListResource(true, 'Data price list Berhasil Diupdate!', $price_list);
        }

        return new OfficePriceListResource(false, 'Data price list Gagal Diupdate!', null);
    }

    public function destroy(Office_price_lists $price_list)
    {
        if($price_list->delete())
        {
            return new OfficePriceListResource(true, 'Data Price List Berhasil Dihapus!', null);
        }

        return new OfficePriceListResource(false, 'Data Price List Gagal Dihapus!', null);
    }

    public function all()
    {
        $price_list = Office_price_lists::latest()->get();

        return new OfficePriceListResource(true, 'List Data Price List', $price_list);
    }

    public function itemDetailPart($part_for)
    {
        $data = Office_part_items::when(request()->search, function ($price_lists) {
            $price_lists = $price_lists->where('item_code', 'like', '%' . request()->search . '%')
            ->orWhere('item_name', 'like', '%' . request()->search . '%')
            ->orWhere('product_code', 'like', '%' . request()->search . '%');
        })->where('part_for', $part_for)->latest()->paginate(10);

        $data->appends(['search' => request()->search]);

        return new OfficePriceListResource(true, 'Part Item : ', $data);
    }

    public function searchPriceList()
    {
         // Subquery untuk price_list_item
         $a = DB::table('office_price_list_item')->select('*');

         $total = DB::table(DB::raw('('.$a->toSql().') as price_list_item'))
             ->mergeBindings($a) // Langsung passing $a tanpa getQuery()
             ->selectRaw('price_list.*, price_list_item.*')
             ->join(DB::raw('(
                 SELECT
                     id as pl_id,
                     code,
                     periode_from,
                     periode_to,
                     is_active,
                     description,
                     unit_item,
                     unit_name,
                     location_id,
                     warehouse_id,
                     warehouse_name
                 FROM office_price_list
             ) as price_list'), function($join) {
                 $join->on('price_list.pl_id', '=', 'price_list_item.price_list_id');
             })
             ->when(request()->search, function ($query) {
                 $query->where(function($q) {
                     $q->where('item_name', 'like', '%'.request()->search.'%')
                       ->orWhere('item_code', 'like', '%'.request()->search.'%')
                       ->orWhere('unit_name', 'like', '%'.request()->search.'%')
                       ->orWhere('code', 'like', '%'.request()->search.'%')
                       ->orWhere('unit_item', 'like', '%'.request()->search.'%');
                 });
             })
             ->latest()
             ->paginate(10);
        return new OfficePriceListResource(true, 'Data : ', $total);
    }

    public function destroyPriceListItem($id, $pl_id)
    {
        $del_pl_item = Office_price_list_items::whereId($id)->where('price_list_id', $pl_id)->delete();

        if($del_pl_item)
        {
            return new OfficePriceListResource(true, 'Data Price List Berhasil Dihapus!', null);
        }

        return new OfficePriceListResource(false, 'Data Price List Gagal Dihapus!', null);
    }

    public function officeResetIndex()
    {
        $data = Office_resets::when(request()->search, function ($query) {
            $query->where(function($q) {
                $q->where('item_name', 'like', '%'.request()->search.'%')
                  ->orWhere('item_code', 'like', '%'.request()->search.'%')
                  ->orWhere('unit_name', 'like', '%'.request()->search.'%')
                  ->orWhere('code', 'like', '%'.request()->search.'%')
                  ->orWhere('unit_item', 'like', '%'.request()->search.'%');
            });
        })
        ->latest()
        ->paginate(10);

        return new OfficePriceListResource(false, 'List data office_reset', $data);
    }

    public function officeResetStore(Request $request)
    {
        $user = auth()->guard('api')->user();
        $office_reset = new Office_resets();
        $office_reset->code = Office_resets::getNextCode();
        $office_reset->user_id = $user->id;
        $office_reset->company_id = $user->company_id;

        $office_reset->periode_from = $request->periode_from ?? null;
        $office_reset->periode_to = $request->periode_to ?? null;
        $office_reset->description = $request->description ?? null;
        if($request->is_submitted == 4){
            $office_reset->is_submitted = $request->is_submitted;
            $office_reset->posting_date = date("Y-m-d H:i:s");
        }else{
            $office_reset->is_submitted = 0;
            $office_reset->user_update = 0;
            $office_reset->posting_date = null;
        }
        $office_reset->is_active = $request->is_active ?? 0;
        $office_reset->save();

        if($request->office_reset_item){
            $items = is_string($request->office_reset_item) ? json_decode($request->office_reset_item, true) : $request->office_reset_item;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['unit'])){
                    $office_reset_item = new Office_reset_items();
                    $office_reset_item->reset_id = $office_reset->id;
                    $office_reset_item->item_name = $item['name'];
                    $office_reset_item->item_code = $item['code'];
                    $office_reset_item->item_unit = $item['unit'];
                    $office_reset_item->product_code = $item['product_code'];
                    $office_reset_item->price = $item['price'] ?? null;
                    $office_reset_item->save();
                    // dd($price_list_item);
                }
            }
        }

        if($office_reset){
            return new OfficePriceListResource(true, 'Data office reset berhasil disimpan', $office_reset);
        }

        return new OfficePriceListResource(false, 'Data gagal disimpan', null);
    }

    public function officeResetShow($id)
    {
        $data = Office_resets::whereId($id)->with('office_reset_items')->first();

        return new OfficePriceListResource(true, 'Data Reset', $data);
    }

    public function officeResetUpdate(Request $request, $id)
    {
        $user = auth()->guard('api')->user();
        $office_reset = Office_resets::whereId($id)->first();

        $office_reset->periode_from = $request->periode_from ?? null;
        $office_reset->periode_to = $request->periode_to ?? null;
        $office_reset->description = $request->description ?? null;
        if($office_reset->is_submitted < 4 && $request->is_submitted == 4){
            $office_reset->is_submitted = $request->is_submitted;
            $office_reset->posting_date = date("Y-m-d H:i:s");
        }else{
            $office_reset->is_submitted = $request->is_submitted;
            $office_reset->user_update = $user->id;
            $office_reset->posting_date = null;
        }
        $office_reset->is_active = $request->is_active ?? 0;
        $office_reset->save();

        if($request->office_reset_item){
            $items = is_string($request->office_reset_item) ? json_decode($request->office_reset_item, true) : $request->office_reset_item;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['idReset'])){
                    $office_reset_item = Office_reset_items::whereId($item['idReset'])->first();
                    $office_reset_item->reset_id = $office_reset->id;
                    $office_reset_item->item_name = $item['name'];
                    $office_reset_item->item_code = $item['code'];
                    $office_reset_item->item_unit = $item['unit'];
                    $office_reset_item->product_code = $item['product_code'];
                    $office_reset_item->price = $item['price'] ?? null;
                    $office_reset_item->save();
                    // dd($price_list_item);
                } else {
                    $office_reset_item = new Office_reset_items();
                    $office_reset_item->reset_id = $office_reset->id;
                    $office_reset_item->item_name = $item['name'];
                    $office_reset_item->item_code = $item['code'];
                    $office_reset_item->item_unit = $item['unit'];
                    $office_reset_item->product_code = $item['product_code'];
                    $office_reset_item->price = $item['price'] ?? null;
                    $office_reset_item->save();
                }
            }
        }
    }

    public function officeConsumableIndex()
    {
        $data = Office_consumables::when(request()->search, function ($query) {
            $query->where(function($q) {
                $q->where('descrption', 'like', '%'.request()->search.'%')
                  ->orWhere('code', 'like', '%'.request()->search.'%');
            });
        })
        ->latest()
        ->paginate(10);

        $data->appends(['search' => request()->search]);

        return new OfficePriceListResource(true, 'Data office consumable', $data);
    }

    public function officeConsumableItem()
    {
        $data = Items::when(request()->search, function ($query) {
            $query->where(function($q) {
                $q->where('item_name', 'like', '%'.request()->search.'%')
                  ->orWhere('item_code', 'like', '%'.request()->search.'%')
                  ->orWhere('unit_name', 'like', '%'.request()->search.'%')
                  ->orWhere('code', 'like', '%'.request()->search.'%')
                  ->orWhere('unit_item', 'like', '%'.request()->search.'%');
            });
        })
        ->where('type_item_office', 3)
        ->latest()
        ->paginate(10);

        $data->appends(['search' => request()->search]);

        return new OfficePriceListResource(true, 'Data item office consumable', $data);
    }

    public function officeConsumableItems($cs_item_id, $item_id)
    {
        $date_now = date("Y-m-d");
    $user = auth()->guard('api')->user();
    $user_Warehouse_id = $request->warehouse_id ?? 0; // Pastikan `warehouse_id` dikirimkan dalam request
    $cs_item_id = $request->cs_item_id ?? 0; // Pastikan `cs_item_id` dikirimkan dalam request
    $item_id = $request->item_id ?? 0; // Pastikan `item_id` dikirimkan dalam request

    // Subquery untuk Office_consumables
    $a = Office_consumables::select('office_consumables.*')
        ->where('office_consumables.is_active', '=', 1)
        ->where('office_consumables.is_submitted', '=', 4)
        ->whereRaw('("' . $date_now . '" between periode_from and periode_to)');

    // Query utama
    $data = DB::table(DB::raw('(' . $a->toSql() . ') as cons'))
        ->mergeBindings($a->getQuery()) // Menggabungkan binding dari subquery
        ->selectRaw('
            cons.*,
            cons_item.*,
            (' . $cs_item_id . ') as cs_item_id,
            (' . $item_id . ') as item_id,
            stock.StockAkhir as stock
        ')
        ->join(DB::raw('(
            select * from (
                select
                    office_consumable_items.id as cons_item_id,
                    office_consumable_items.consumable_id,
                    office_consumable_items.item_code,
                    office_consumable_items.item_name,
                    office_consumable_items.item_unit,
                    office_consumable_items.product_code,
                    office_consumable_items.price,
                    office_consumable_items.type_item_office,
                    ' . $user_Warehouse_id . ' as warehouse_id,
                    (select name from warehouse where warehouse.id=' . $user_Warehouse_id . ' limit 1) as warehouse_name,
                    (select category_office from items where items.id=office_consumable_items.item_code limit 1) as category_office
                from office_consumable_items
                order by office_consumable_items.updated_at desc
                limit 1000000
            ) as ofi
            group by ofi.item_code, ofi.item_unit
        ) as cons_item'), function ($join) {
            $join->on('cons.id', '=', 'cons_item.consumable_id');
        })
        ->leftJoin(DB::raw('(
            select
                b.*,
                round(sum(b.quantity), 2) as StockAkhir,
                round(sum(b.total), 2) as SaldoAkhir
            from (
                select
                    inventory.item_code,
                    inventory.item_name,
                    inventory.item_unit,
                    round(sum(qty), 2) as quantity,
                    round(sum(total_price), 2) as total,
                    transactions.warehouse_id,
                    transactions.date_transaction as Tanggal
                from transactions
                join inventory on transactions.id = inventory.transaction_id
                where
                    transactions.transaction_type <> 14 and
                    transactions.transaction_type <> 15 and
                    transactions.warehouse_id = ' . $user_Warehouse_id . ' and
                    transactions.company_id = ' . $user->company_id . ' and
                    date(transactions.date_transaction) > "2017-08-31"
                group by inventory.item_code
            ) as b
            group by b.item_code
            order by b.item_code asc
        ) as stock'), function ($join) {
            $join->on('cons_item.item_code', '=', 'stock.item_code')
                 ->on('cons_item.warehouse_id', '=', 'stock.warehouse_id');
        })
        ->when(request()->search, function ($data) {
                    $data = $data->where('cons_item.item_code', 'like', '%' . request()->search . '%')
                        ->orWhere('cons_item.item_name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        if($data){
            return new OfficePriceListResource(true, 'Success', $data);
        }

        return new OfficePriceListResource(false, 'Failed', null);
    }

    public function officeConsumableShow($id)
    {
        $data = Office_consumables::whereId($id)->with('office_consumable_items')->first();

        if($data){
            return new OfficePriceListResource(true, 'Detail data office consumbale', $data);
        }

        return new OfficePriceListResource(false, 'Detail data office consumbale tidak ditemukan', null);

    }

    public function officeConsumableStore(Request $request)
    {
        $user = auth()->guard('api')->user();
        $office_consumable = new Office_consumables();
        $office_consumable->code = Office_consumables::getNextCode();
        $office_consumable->user_id = $user->id;
        $office_consumable->company_id = $user->company_id;

        $office_consumable->periode_from = $request->periode_from ?? null;
        $office_consumable->periode_to = $request->periode_to ?? null;
        $office_consumable->description = $request->description ?? '';
        if($request->is_submitted == 4){
            $office_consumable->is_submitted = $request->is_submitted;
            $office_consumable->posting_date = date("Y-m-d H:i:s");
        }else{
            $office_consumable->is_submitted = $request->is_submitted;
            $office_consumable->posting_date = null;
        }

        $office_consumable->is_active = $request->is_active ?? 0;
        $office_consumable->save();

        if($request->office_consumable_items){
            $items = is_string($request->office_consumable_items) ? json_decode($request->office_consumable_items, true) : $request->office_consumable_items;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['unit'])){
                    $office_consumable_item = new Office_consumable_items();
                    $office_consumable_item->consumable_id = $office_consumable->id;
                    $office_consumable_item->item_name = $item['name'];
                    $office_consumable_item->item_code = $item['code'];
                    $office_consumable_item->item_unit = $item['unit'];
                    $office_consumable_item->product_code = $item['product_code'];
                    $office_consumable_item->type_item_office = $item['type_item_office'];
                    $office_consumable_item->price = $item['price'] ?? null;
                    $office_consumable_item->save();
                    // dd($price_list_item);
                }
            }
        }

        if($office_consumable){
            return new OfficePriceListResource(true, 'Office consumable berhasil disimpan', $office_consumable);
        }

        return new OfficePriceListResource(false, 'Gagal disimpan', null);
    }

    public function officeConsumableUpdate(Request $request, $id)
    {
        $user = auth()->guard('api')->user();
        $office_consumable = Office_consumables::whereId($id)->first();

        $office_consumable->periode_from = $request->periode_from ?? null;
        $office_consumable->periode_to = $request->periode_to ?? null;
        $office_consumable->description = $request->description ?? '';
        if($request->is_submitted == 4){
            $office_consumable->is_submitted = $request->is_submitted;
            $office_consumable->user_posting = $user->name;
            $office_consumable->posting_date = date("Y-m-d H:i:s");
        }

        $office_consumable->is_active = $request->is_active ?? 0;
        $office_consumable->save();

        if($request->office_consumable_items){
            $items = is_string($request->office_consumable_items) ? json_decode($request->office_consumable_items, true) : $request->office_consumable_items;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['idConsumbale'])){
                    $office_consumable_item = Office_consumable_items::whereId($item['idConsumable'])->first();
                    $office_consumable_item->consumable_id = $office_consumable->id;
                    $office_consumable_item->item_name = $item['name'];
                    $office_consumable_item->item_code = $item['code'];
                    $office_consumable_item->item_unit = $item['unit'];
                    $office_consumable_item->product_code = $item['product_code'];
                    $office_consumable_item->type_item_office = $item['type_item_office'];
                    $office_consumable_item->price = $item['price'] ?? null;
                    $office_consumable_item->save();
                    // dd($price_list_item);
                }else if(!empty($item['unit'])){
                    $office_consumable_item = new Office_consumable_items();
                    $office_consumable_item->consumable_id = $office_consumable->id;
                    $office_consumable_item->item_name = $item['name'];
                    $office_consumable_item->item_code = $item['code'];
                    $office_consumable_item->item_unit = $item['unit'];
                    $office_consumable_item->product_code = $item['product_code'];
                    $office_consumable_item->type_item_office = $item['type_item_office'];
                    $office_consumable_item->price = $item['price'] ?? null;
                    $office_consumable_item->save();
                    // dd($price_list_item);
                }
            }
        }

        if($office_consumable){
            return new OfficePriceListResource(true, 'Office consumable berhasil diperbarui', $office_consumable);
        }

        return new OfficePriceListResource(false, 'Gagal diperbarui', null);
    }

    public function priceListSOIndex()
    {
        $data = Office_so_price_lists::when(request()->search, function ($query) {
            $query->where(function($q) {
                $q->where('descrption', 'like', '%'.request()->search.'%')
                  ->orWhere('code', 'like', '%'.request()->search.'%');
            });
        })
        ->orderBy('code', 'desc')
        ->latest()
        ->paginate(10);

        $data->appends(['search' => request()->search]);

        return new OfficePriceListResource(true, 'Data item price list SO', $data);
    }

    public function priceListSOShow($id)
    {
        $data = Office_so_price_lists::whereId($id)->with('office_so_price_list_items', 'office_so_price_list_whss')->first();
        // dd($data);

        if($data){
            return new OfficePriceListResource(true, 'Detail data price list SO', $data);
        }

        return new OfficePriceListResource(false, 'Detail data price list SO tidak ditemukan', null);
    }

    public function priceListSOStore(Request $request)
    {
        $user = auth()->guard('api')->user();
        $priceListSO = new Office_so_price_lists();
        $priceListSO->code = Office_so_price_lists::getNextCode();
        $priceListSO->user_id = $user->id;
        $priceListSO->company_id = $user->company_id;

        $priceListSO->periode_from = $request->periode_from ?? null;
        $priceListSO->periode_to = $request->periode_to ?? null;
        $priceListSO->description = $request->description ?? null;
        if($request->is_submitted == 4){
            $priceListSO->is_submitted = $request->is_submitted;
            $priceListSO->posting_date = date("Y-m-d H:i:s");
        }else{
            $priceListSO->is_submitted = $request->is_submitted;
            $priceListSO->posting_date = null;
        }
        $priceListSO->is_active = $request->is_active ?? null;
        $priceListSO->save();

        if($priceListSO){
            return new OfficePriceListResource(true, 'Price List SO berhasil disimpan', $priceListSO);
        }
        return new OfficePriceListResource(false, 'Price List SO gagal disimpan', null);
    }

    public function priceListSOUpdate(Request $request, $id)
    {
        $user = auth()->guard('api')->user();
        $priceListSO = Office_so_price_lists::whereId($id)->first();
        $priceListSO->user_id = $user->id;
        $priceListSO->company_id = $user->company_id;

        $priceListSO->periode_from = $request->periode_from ?? null;
        $priceListSO->periode_to = $request->periode_to ?? null;
        $priceListSO->description = $request->description ?? null;
        if($request->is_submitted == 4){
            $priceListSO->is_submitted = $request->is_submitted;
            $priceListSO->posting_date = date("Y-m-d H:i:s");
        }else{
            $priceListSO->is_submitted = $request->is_submitted;
            $priceListSO->posting_date = null;
        }
        $priceListSO->is_active = $request->is_active ?? null;
        $priceListSO->save();

        if($request->price_list_so_items){
            $items = is_string($request->price_list_so_items) ? json_decode($request->price_list_so_items, true) : $request->price_list_so_items;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['so_id'])){
                    $price_list_so_item = Office_so_price_list_items::whereId($item['so_id'])->first();
                    $price_list_so_item->price_list_id = $priceListSO->id;
                    $price_list_so_item->item_name = $item['name'];
                    $price_list_so_item->item_code = $item['code'];
                    $price_list_so_item->item_unit = $item['unit'];
                    $price_list_so_item->product_code = $item['product_code'] ?? null;
                    $price_list_so_item->price = $item['price'] ?? null;
                    $price_list_so_item->save();
                    // dd($price_list_item);
                }else if(!empty($item['unit'])){
                    $price_list_so_item = new Office_so_price_list_items();
                    $price_list_so_item->price_list_id = $priceListSO->id;
                    $price_list_so_item->item_name = $item['name'];
                    $price_list_so_item->item_code = $item['code'];
                    $price_list_so_item->item_unit = $item['unit'];
                    $price_list_so_item->product_code = $item['product_code'] ?? null;
                    $price_list_so_item->price = $item['price'] ?? null;
                    $price_list_so_item->save();
                    // dd($price_list_item);
                }
            }
        }

        if($request->price_list_so_whss){
            $items = is_string($request->price_list_so_whss) ? json_decode($request->price_list_so_whss, true) : $request->price_list_so_whss;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $item) {
                if(!empty($item['so_id'])){
                    $price_list_so_whs = Office_so_price_list_whss::whereId($item['so_id'])->first();
                    $price_list_so_whs->price_list_id = $priceListSO->id;
                    $price_list_so_whs->warehouse_id = $item['id'];
                    $price_list_so_whs->save();
                    // dd($price_list_item);
                }else if(!empty($item['id'])){
                    $price_list_so_whs = new Office_so_price_list_whss();
                    $price_list_so_whs->price_list_id = $priceListSO->id;
                    $price_list_so_whs->warehouse_id = $item['id'];
                    $price_list_so_whs->save();
                    // dd($price_list_item);
                }
            }
        }

        if($priceListSO){
            return new OfficePriceListResource(true, 'Price List SO berhasil diperbarui', $priceListSO);
        }
        return new OfficePriceListResource(false, 'Price List SO gagal diperbarui', null);
    }
}
