<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        if($user->user_group_id == 1){
            $warehouses = Warehouses::when(request()->search, function ($warehouses) {
                $warehouses = $warehouses->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('code', 'like', '%' . request()->search . '%');
            })->with('location')->latest()->paginate(10);
        } else if($user->user_group_id == 19){
            $warehouses = Warehouses::where('company_id', $user->company_id)
            ->when(request()->search, function ($warehouses) {
                $warehouses = $warehouses->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('code', 'like', '%' . request()->search . '%');
            })->with('location')->latest()->paginate(10);
        } else {
            $warehouses = Warehouses::where('company_id', $user->company_id)->where('is_active', 1)
            ->when(request()->search, function ($warehouses) {
                $warehouses = $warehouses->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('code', 'like', '%' . request()->search . '%');
            })->with('location')->latest()->paginate(10);
        }

        $warehouses->appends(['search' => request()->search]);

        return new WarehouseResource(true, 'List data warehouse!', $warehouses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'warehouse_type' => 'required',
            'warehouse_central_type' => 'required_if:warehouse_type,1',
            'location_id' => 'required',
            'currency' => 'required',
            'dimention1' => 'required',
            'dimention2' => 'required',
            'dimention3' => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();
        $user_id = $user ? $user->id : null;
        $user_company_id = $user ? $user->company_id : null;

        $warehouse = new Warehouses();
        $warehouse->warehouse_type = $request->warehouse_type;
        if($request->warehouse_type == 1){
            $warehouse->warehouse_central_type = $request->warehouse_central_type;
        }
        $warehouse->location_id = $request->location_id;
        $warehouse->code = Warehouses::getNextCodeId($warehouse->location_id, $warehouse->warehouse_type);
        $warehouse->name = $request->name;
        $warehouse->currency = $request->currency;
        $warehouse->dimention1 = is_array($request->dimention1) ? implode(',', $request->dimention1) : $request->dimention1;
        $warehouse->dimention2 = is_array($request->dimention2) ? implode(',', $request->dimention2) : $request->dimention2;
        $warehouse->dimention3 = is_array($request->dimention3) ? implode(',', $request->dimention3) : $request->dimention3;
        $warehouse->user_id = $user_id;
        $warehouse->company_id = $user_company_id;
        $warehouse->is_active = $request->is_active;
        $warehouse->customer_id = $request->customer_id ?? 0;
        $warehouse->flag = $request->flag ?? 0;
        $warehouse->is_warranty = $request->is_warranty ?? 0;
        $warehouse->is_branch = $request->is_branch ?? 0;
        $warehouse->warehouse_type_wrt = $request->warehouse_type_wrt ?? 0;
        $warehouse->central_warehouse_id = $request->central_warehouse_id ?? 0;
        $warehouse->save();

        if($warehouse){
            return new WarehouseResource(true, 'Data Warehouse Berhasil Dibuat!', $warehouse);
        }

        return new WarehouseResource(false, 'Data Warehouse Gagal Dibuat', null);
    }

    public function show($id)
    {
        $warehouse = Warehouses::whereId($id)->first();

        if($warehouse){
            return new WarehouseResource(true, 'Data Warehouse Ditemukan!', $warehouse);
        }

        return new WarehouseResource(false, 'Data Warehouse Tidak Ditemukan!', null);
    }

    public function stores_central(){
        $user = auth()->guard('api')->user();
        $user_group_id = $user->user_group_id;
        $user_company_id = $user->company_id;

        if($user_group_id == 1){
            $centralStore = Warehouses::where('warehouse_type', 2)->latest()->get();
        }else if ($user_group_id == 19){
            $centralStore = Warehouses::where('company_id', $user_company_id)->latest()->get();
        }else{
            $centralStore = Warehouses::where([
                ['company_id', '=', $user_company_id],
                ['warehouse_type', '=', 2],
                ['is_active', '=', 1],
            ])->latest()->get();
        }

        // dd($user);

        if($centralStore){
            return new WarehouseResource(true, 'List data central store', $centralStore);
        }

        return new WarehouseResource(false, 'Data central store tidak ditemukan', null);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_warranty' => 'required',
            'central_warehouse_id' => 'required_if:warehouse_type,1',
            'name' => 'required',
            'dimention1' => 'required',
            'dimention2' => 'required',
            'dimention3' => 'required',
            'currency' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();
        $user_id = $user ? $user->id : null;
        $user_company_id = $user ? $user->company_id : null;

        $warehouse = Warehouses::whereId($id)->first();
        $warehouse->warehouse_type = $request->warehouse_type;
        if($request->warehouse_type == 1){
            $warehouse->warehouse_central_type = $request->warehouse_central_type;
        }
        $warehouse->location_id = $request->location_id;
        $warehouse->name = $request->name;
        $warehouse->currency = $request->currency;
        $warehouse->dimention1 = is_array($request->dimention1) ? implode(',', $request->dimention1) : $request->dimention1;
        $warehouse->dimention2 = is_array($request->dimention2) ? implode(',', $request->dimention2) : $request->dimention2;
        $warehouse->dimention3 = is_array($request->dimention3) ? implode(',', $request->dimention3) : $request->dimention3;
        $warehouse->user_id = $user_id;
        $warehouse->company_id = $user_company_id;
        $warehouse->is_active = $request->is_active;
        $warehouse->customer_id = $request->customer_id ?? 0;
        $warehouse->flag = $request->flag ?? 0;
        $warehouse->is_warranty = $request->is_warranty ?? 0;
        $warehouse->is_branch = $request->is_branch ?? 0;
        $warehouse->warehouse_type_wrt = $request->warehouse_type_wrt ?? 0;
        $warehouse->central_warehouse_id = $request->central_warehouse_id ?? 0;
        $warehouse->save();

        if($warehouse){
            return new WarehouseResource(true, 'Data Warehouse Berhasil Diperbarui!', $warehouse);
        }

        return new WarehouseResource(false, 'Data Warehouse Gagal Diperbarui', null);
    }

    public function destroy(Warehouses $warehouse)
    {
        if($warehouse->delete()){
            return new WarehouseResource(true, 'Data Warehouse Berhasil Dihapus', null);
        }

        return new WarehouseResource(false, 'Data Warehouse Gagal Dihapus', null);
    }

    public function all()
    {
        $warehouses = Warehouses::latest()->get();

        return new WarehouseResource(true, 'List Data Warehouse', $warehouses);
    }

    public function withFlagandWarranty($flag, $warranty)
    {
        $user = auth()->guard('api')->user();
        if($user->user_group_id == 1){
            $warehouses = Warehouses::where('flag', $flag)->where('is_warranty', $warranty)
            ->get();
        } else if($user->user_group_id == 19){
            $warehouses = Warehouses::where('flag', $flag)->where('is_warranty', $warranty)->where('company_id', $user->company_id)
            ->get();
        } else {
            $warehouses = Warehouses::where('flag', $flag)->where('is_warranty', $warranty)->where('company_id', $user->company_id)->where('is_active', 1)
            ->get();
        }

        $warehouses->appends(['search' => request()->search]);

        return new WarehouseResource(true, 'List data warehouse!', $warehouses);
    }

    public function warehouseWarranty($is_branch, $location_id)
    {
        $user = auth()->guard('api')->user();
        $user_group_id = $user->group_id;
        $user_company_id = $user->company_id;

        if($is_branch == 0){
			if($user_group_id == 1){
				$data = Warehouses::where('flag','=',3)
							->where('is_warranty','=',1);
			}else if($user_group_id == 19){
				$data = Warehouses::where('company_id','=',$user_company_id)
							->where('flag','=',3)
							->where('is_warranty','=',1);
			}else{
				$data = Warehouses::where('company_id','=',$user_company_id)
						->where('is_active', 1)
						->where('flag','=',3)
						->where('is_warranty','=',1);
			}
		}else{
			if($user_group_id == 1){
				$data = Warehouses::where('flag','=',3)
							->where('is_warranty','=',1)
							->where('is_branch','=',1)
							->where('location_id','=',$location_id);
			}else if($user_group_id == 19){
				$data = Warehouses::where('company_id','=',$user_company_id)
							->where('flag','=',3)
							->where('is_warranty','=',1)
							->where('is_branch','=',1)
							->where('location_id','=',$location_id);
			}else{
				$data = Warehouses::where('company_id','=',$user_company_id)
						->where('is_active', 1)
						->where('flag','=',3)
						->where('is_warranty','=',1)
						->where('is_branch','=',1)
						->where('location_id','=',$location_id);
			}
		}

        if($data){
            $data = $data->get();
            return new WarehouseResource(true, 'List data warehouse warranty!', $data);
        }

        return new WarehouseResource(false, 'Not Found', null);
    }
}
