<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Itemgroups;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemSuperGroupResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemSuperGroupController extends Controller
{
    public function index()
    {
        $itemsupergroups = Itemgroups::when(request()->search, function ($itemsupergroups) {
            $itemsupergroups = $itemsupergroups->where('name', 'like', '%' . request()->search . '%');
        })->orderBy('code', 'asc')->latest()->paginate(10);

        $itemsupergroups->appends(['search' => request()->search]);

        return new ItemSuperGroupResource(true, 'List data Item Group', $itemsupergroups);
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

        $itemsupergroup = new Itemgroups();
        $itemsupergroup->name = $request->name;
        $itemsupergroup->is_active = $request->is_active;
        $itemsupergroup->code = Itemgroups::getNextCounterId();
        $itemsupergroup->save();

        if($itemsupergroup)
        {
            return new ItemSuperGroupResource(true, 'Data Item Super Group Berhasil Disimpan!', $itemsupergroup);
        }

        return new ItemSuperGroupResource(false, 'Data Item Super Group Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $itemsupergroup = Itemgroups::whereId($id)->first();

        if($itemsupergroup)
        {
            return new ItemSuperGroupResource(true, 'Detail Data Item Super Group!', $itemsupergroup);
        }

        return new ItemSuperGroupResource(false, 'Detail Data Item Super Group Tidak Ditemukan!', null);
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

        $itemsupergroup = Itemgroups::whereId($id)->first();

        $itemsupergroup->name = $request->name;
        $itemsupergroup->is_active = $request->is_active;

        $itemsupergroup->save();


        if($itemsupergroup)
        {
            return new ItemSuperGroupResource(true, 'Data Item Super Group Berhasil Diupdate!', $itemsupergroup);
        }

        return new ItemSuperGroupResource(false, 'Data Item Super Group Gagal Diupdate!', null);
    }

    public function destroy(Itemgroups $itemsupergroup)
    {
        if($itemsupergroup->delete())
        {
            return new ItemSuperGroupResource(true, 'Data Item Super Group Berhasil Dihapus!', null);
        }

        return new ItemSuperGroupResource(false, 'Data Item Super Group Gagal Dihapus!', null);
    }

    public function all()
    {
        $Itemsupergroups = Itemgroups::latest()->get();

        return new ItemSuperGroupResource(true, 'List Data Item Super Group', $Itemsupergroups);
    }
}
