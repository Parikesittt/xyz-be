<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Customer_markets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerMarketResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerMarketController extends Controller
{


    public function index()
    {
        $customermarket = Customer_markets::get();

        return new CustomerMarketResource(true, 'List data customer Market', $customermarket);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'name'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $customermarket = new Customer_markets();
        $customermarket->code = strtoupper($request->code);
        $customermarket->name = strtoupper($request->name);
        $customermarket->is_active = $request->is_active;
        $customermarket->save();

        if($customermarket)
        {
            return new CustomerMarketResource(true, 'Data Customer Market Berhasil Disimpan!', $customermarket);
        }

        return new CustomerMarketResource(false, 'Data Customer Market Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $customermarket = Customer_markets::whereId($id)->first();

        if($customermarket)
        {
            return new CustomerMarketResource(true, 'Detail Data Customer Market!', $customermarket);
        }

        return new CustomerMarketResource(false, 'Detail Data Customer Market Tidak Ditemukan!', null);
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

        $customermarket = Customer_markets::whereId($id)->first();

        $customermarket->code = strtoupper($request->code);
        $customermarket->name = strtoupper($request->name);
        $customermarket->is_active = $request->is_active;
        $customermarket->save();


        if($customermarket)
        {
            return new CustomerMarketResource(true, 'Data Customer Market Berhasil Diupdate!', $customermarket);
        }

        return new CustomerMarketResource(false, 'Data Customer Market Gagal Diupdate!', null);
    }

    public function destroy(Customer_markets $customermarket)
    {
        if($customermarket->delete())
        {
            return new CustomerMarketResource(true, 'Data Customer Market Berhasil Dihapus!', null);
        }

        return new CustomerMarketResource(false, 'Data Customer Market Gagal Dihapus!', null);
    }

    public function all()
    {
        $Customer_markets = Customer_markets::latest()->get();

        return new CustomerMarketResource(true, 'List Data Customer Market', $Customer_markets);
    }
}
