<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Locations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
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
        $locations = Locations::when(request()->search, function ($locations) {
            $locations = $locations->where('name', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%');
        })->orderBy('code', 'asc')->latest()->paginate(10);

        $locations->appends(['search' => request()->search]);

        return new LocationResource(true, 'List data locations', $locations);
    }

    public function store(Request $request)
    {
        Log::info('Incoming Request Data:', $request->all());
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'city_name'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user()->only(['id', 'company_id']);

        $location = new Locations();
        $location->code = $request->code;
        $location->name = $request->name;
        $location->dimention_type = $request->dimention_type;
        $location->city_name = $request->city_name;
        $location->daily_pob_estimated = $request->daily_pob;
        $location->price_pob_estimated = $request->price_pob;
        $location->backcharge = $request->backcharge;
        $location->company_id = $user['company_id'];
        $location->user_id = $user['id'];
        $location->is_active = $request->is_active;
        $location->save();

        if($location)
        {
            return new LocationResource(true, 'Data Location Berhasil Disimpan!', $location);
        }

        return new LocationResource(false, 'Data Location Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $location = Locations::whereId($id)->first();

        if($location)
        {
            return new LocationResource(true, 'Detail Data Location!', $location);
        }

        return new LocationResource(false, 'Detail Data Location Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $location = Locations::whereId($id)->first();

        $user = auth()->guard('api')->user()->only(['id', 'company_id']);

        $location->code = $request->code;
        $location->name = $request->name;
        $location->dimention_type = $request->dimention_type;
        $location->city_name = $request->city_name;
        $location->daily_pob_estimated = $request->daily_pob;
        $location->price_pob_estimated = $request->price_pob;
        $location->backcharge = $request->backcharge;
        $location->company_id = $user['company_id'];
        $location->user_id = $user['id'];
        $location->is_active = $request->is_active;
        $location->save();


        // if($location)
        // {
        //     return new LocationResource(true, 'Data Location Berhasil Diupdate!', $location);
        // }

        // return new LocationResource(false, 'Data Location Gagal Diupdate!', null);

        return response()->json([
            'data' => $request->all(),
            'message' => 'Data received successfully'
        ]);
    }

    public function destroy(Locations $location)
    {
        if($location->delete())
        {
            return new LocationResource(true, 'Data Location Berhasil Dihapus!', null);
        }

        return new LocationResource(false, 'Data Location Gagal Dihapus!', null);
    }

    public function all()
    {
        $locations = Locations::latest()->get();

        return new LocationResource(true, 'List Data Location', $locations);
    }
}
