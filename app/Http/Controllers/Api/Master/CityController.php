<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Citys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class CityController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('can:company.index', ['only' => ['index']]);
    //     $this->middleware('can:company.create', ['only' => ['store']]);
    //     $this->middleware('can:company.edit', ['only' => ['show, update']]);
    //     $this->middleware('can:company.delete', ['only' => ['destroy']]);
    // }

    public function index()
    {
        $cities = Citys::when(request()->search, function ($cities) {
            $cities = $cities->where('name', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%');
        })->latest()->orderBy('id', 'asc')->paginate(10);

        $cities->appends(['search' => request()->search]);

        return new CityResource(true, 'List data company', $cities);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $city = new Citys();
        $city->code = $request->code;
        $city->name = $request->name;
        $city->is_active = $request->is_active;
        $city->save();

        if($city)
        {
            return new CityResource(true, 'Data City Berhasil Disimpan!', $city);
        }

        return new CityResource(false, 'Data City Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $city = Citys::whereId($id)->first();

        if($city)
        {
            return new CityResource(true, 'Detail Data City!', $city);
        }

        return new CityResource(false, 'Detail Data City Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {
        Log::info('Incoming Request Data:', $request->all());
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $City = Citys::whereId($id)->first();

        $City->code = $request->code;
        $City->name = $request->name;
        $City->is_active = $request->is_active;
        $City->save();

        if($City)
        {
            return new CityResource(true, 'Data City Berhasil Diupdate!', $City);
        }

        return new CityResource(false, 'Data City Gagal Diupdate!', null);
    }

    public function destroy(Citys $City)
    {
        if($City->delete())
        {
            return new CityResource(true, 'Data City Berhasil Dihapus!', null);
        }

        return new CityResource(false, 'Data City Gagal Dihapus!', null);
    }

    public function all()
    {
        $cities = Citys::latest()->get();

        return new CityResource(true, 'List Data City!', $cities);
    }
}
