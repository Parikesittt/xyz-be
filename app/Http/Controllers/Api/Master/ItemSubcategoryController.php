<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Item_subcategorys;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemSubcategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemSubcategoryController extends Controller
{
    public function index()
    {
        $item_subs = Item_subcategorys::when(request()->search, function ($item_subs) {
            $item_subs = $item_subs->where('name', 'like', '%' . request()->search . '%');
        })->orderBy('id', 'asc')->latest()->paginate(10);

        $item_subs->appends(['search' => request()->search]);

        return new ItemSubcategoryResource(true, 'List data Item Subcategory', $item_subs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $item_sub = new Item_subcategorys();
        $item_sub->name = $request->name;
        $item_sub->is_active = $request->is_active;
        $item_sub->save();

        if($item_sub)
        {
            return new ItemSubcategoryResource(true, 'Data Item Subcategory Berhasil Disimpan!', $item_sub);
        }

        return new ItemSubcategoryResource(false, 'Data Item Subcategory Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $item_sub = Item_subcategorys::whereId($id)->first();

        if($item_sub)
        {
            return new ItemSubcategoryResource(true, 'Detail Data Item Subcategory!', $item_sub);
        }

        return new ItemSubcategoryResource(false, 'Detail Data Item Subcategory Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $item_sub = Item_subcategorys::whereId($id)->first();

        $item_sub->name = $request->name;
        $item_sub->is_active = $request->is_active;

        $item_sub->save();


        if($item_sub)
        {
            return new ItemSubcategoryResource(true, 'Data Item Subcategory Berhasil Diupdate!', $item_sub);
        }

        return new ItemSubcategoryResource(false, 'Data Item Subcategory Gagal Diupdate!', null);
    }

    public function destroy(Item_subcategorys $item_sub)
    {
        if($item_sub->delete())
        {
            return new ItemSubcategoryResource(true, 'Data Item Subcategory Berhasil Dihapus!', null);
        }

        return new ItemSubcategoryResource(false, 'Data Item Subcategory Gagal Dihapus!', null);
    }

    public function all()
    {
        $Item_subcategorys = Item_subcategorys::latest()->get();

        return new ItemSubcategoryResource(true, 'List Data Item Subcategory', $Item_subcategorys);
    }
}
