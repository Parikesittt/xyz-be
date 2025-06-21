<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Itemcategorys;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemGroupResource;
use App\Models\Itemgroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemGroupController extends Controller
{
    public function index()
    {
        $itemcategories = Itemcategorys::when(request()->search, function ($itemcategories) {
            $itemcategories = $itemcategories->where('name', 'like', '%' . request()->search . '%');
        })->orderBy('code', 'asc')->latest()->paginate(10);

        $itemcategories->appends(['search' => request()->search]);

        return new ItemGroupResource(true, 'List data Item Group', $itemcategories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'itemgroup_id' => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $itemgroups = Itemgroups::whereId($request->itemgroup_id)->first();

        $itemcategory = new Itemcategorys();
        $itemcategory->name = $request->name;
        $itemcategory->itemgroup_id = $request->itemgroup_id;
        $itemcategory->is_active = $request->is_active;
        $itemcategory->code = Itemcategorys::getNextCounterId($itemgroups->code);
        $itemcategory->save();

        if($itemcategory)
        {
            return new ItemGroupResource(true, 'Data Item Group Berhasil Disimpan!', $itemcategory);
        }

        return new ItemGroupResource(false, 'Data Item Group Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $itemcategory = Itemcategorys::whereId($id)->first();

        if($itemcategory)
        {
            return new ItemGroupResource(true, 'Detail Data Item Group!', $itemcategory);
        }

        return new ItemGroupResource(false, 'Detail Data Item Group Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'itemgroup_id' => 'required',
            'is_active' => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $itemgroups = Itemgroups::whereId($request->itemgroup_id)->first();

        $itemcategory = Itemcategorys::whereId($id)->first();

        $itemcategory->name = $request->name;
        $itemcategory->itemgroup_id = $request->itemgroup_id;
        $itemcategory->is_active = $request->is_active;

        $itemcategory->save();


        if($itemcategory)
        {
            return new ItemGroupResource(true, 'Data Item Group Berhasil Diupdate!', $itemcategory);
        }

        return new ItemGroupResource(false, 'Data Item Group Gagal Diupdate!', null);
    }

    public function destroy(Itemcategorys $itemcategory)
    {
        if($itemcategory->delete())
        {
            return new ItemGroupResource(true, 'Data Item Group Berhasil Dihapus!', null);
        }

        return new ItemGroupResource(false, 'Data Item Group Gagal Dihapus!', null);
    }

    public function all()
    {
        $itemcategories = Itemcategorys::latest()->get();

        return new ItemGroupResource(true, 'List Data Item Group', $itemcategories);
    }
}
