<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Item_sales_taxs;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemSalesTaxResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemSalesTaxController extends Controller
{
    public function index()
    {
        $item_sales = Item_sales_taxs::when(request()->search, function ($item_sales) {
            $item_sales = $item_sales->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $item_sales->appends(['search' => request()->search]);

        return new ItemSalesTaxResource(true, 'List data item sales tax', $item_sales);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
            'is_active'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $busType = new Item_sales_taxs();
        $busType->name = strtoupper($request->name);
        $busType->code = strtoupper($request->code);
        $busType->company_id = $user->company_id;
        $busType->saved_id = $user->id;
        $busType->is_active = $request->is_active;
        $busType->save();

        if($busType)
        {
            return new ItemSalesTaxResource(true, 'Data Business Type Berhasil Disimpan!', $busType);
        }

        return new ItemSalesTaxResource(false, 'Data Business Type Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $busType = Item_sales_taxs::whereId($id)->first();

        if($busType)
        {
            return new ItemSalesTaxResource(true, 'Detail Data Business Type!', $busType);
        }

        return new ItemSalesTaxResource(false, 'Detail Data Business Type Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'code' => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $busType = Item_sales_taxs::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $busType->name = $request->name;
        $busType->code = $request->code;
        $busType->company_id = $user->company_id;
        $busType->updated_id = $user->id;
        $busType->is_active = $request->is_active;

        $busType->save();


        if($busType)
        {
            return new ItemSalesTaxResource(true, 'Data Business Type Berhasil Diupdate!', $busType);
        }

        return new ItemSalesTaxResource(false, 'Data Business Type Gagal Diupdate!', null);
    }

    public function destroy(Item_sales_taxs $busType)
    {
        if($busType->delete())
        {
            return new ItemSalesTaxResource(true, 'Data Business Type Berhasil Dihapus!', null);
        }

        return new ItemSalesTaxResource(false, 'Data Business Type Gagal Dihapus!', null);
    }

    public function all()
    {
        $busTypes = Item_sales_taxs::latest()->get();

        return new ItemSalesTaxResource(true, 'List Data Business Type', $busTypes);
    }
}
