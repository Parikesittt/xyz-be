<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Office_costs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeCostResource;
use App\Models\Office_cost_items;
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
        $items =$request->office_cost_item;
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
}
