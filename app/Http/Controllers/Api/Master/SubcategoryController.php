<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Subcategorys;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubcategoryResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subs = Subcategorys::when(request()->search, function ($subs) {
            $subs = $subs->where('name', 'like', '%' . request()->search . '%');
        })->orderBy('code', 'asc')->latest()->paginate(10);

        $subs->appends(['search' => request()->search]);

        return new SubcategoryResource(true, 'List data subcategory', $subs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'itemgroup_id' => 'required',
            'itemcategory_id' => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $itemcategory = Itemcategorys::whereId($request->itemcategory_id)->first();

        $sub = new Subcategorys();
        $sub->name = $request->name;
        $sub->itemgroup_id = $request->itemgroup_id;
        $sub->itemcategory_id = $request->itemcategory_id;
        $sub->is_active = $request->is_active;
        $sub->code = Subcategorys::getNextCounterId($itemcategory->code);
        $sub->save();

        if($sub)
        {
            return new SubcategoryResource(true, 'Data Subcategory Berhasil Disimpan!', $sub);
        }

        return new SubcategoryResource(false, 'Data Subcategory Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $sub = Subcategorys::whereId($id)->first();

        if($sub)
        {
            return new SubcategoryResource(true, 'Detail Data Subcategory!', $sub);
        }

        return new SubcategoryResource(false, 'Detail Data Subcategory Tidak Ditemukan!', null);
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

        $sub = Subcategorys::whereId($id)->first();

        $sub->name = $request->name;
        $sub->is_active = $request->is_active;

        $sub->save();


        if($sub)
        {
            return new SubcategoryResource(true, 'Data Subcategory Berhasil Diupdate!', $sub);
        }

        return new SubcategoryResource(false, 'Data Subcategory Gagal Diupdate!', null);
    }

    public function destroy(Subcategorys $sub)
    {
        if($sub->delete())
        {
            return new SubcategoryResource(true, 'Data Subcategory Berhasil Dihapus!', null);
        }

        return new SubcategoryResource(false, 'Data Subcategory Gagal Dihapus!', null);
    }

    public function all()
    {
        $subs = Subcategorys::latest()->get();

        return new SubcategoryResource(true, 'List Data Subcategory', $subs);
    }
}
