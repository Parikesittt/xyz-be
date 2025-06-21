<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Ticketings;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketingResource;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketingController extends Controller
{
    public function index()
    {
        $ticketing = Ticketings::when(request()->search, function ($ticketing) {
            $ticketing = $ticketing->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $ticketing->appends(['search' => request()->search]);

        return new TicketingResource(true, 'List data Ticketing', $ticketing);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'is_active'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $ticketing = new Ticketings();
        $ticketing->name = strtoupper($request->name);
        $ticketing->company_id = $user->company_id;
        $ticketing->saved_id = $user->id;
        $ticketing->is_active = $request->is_active;
        $ticketing->save();

        if($ticketing)
        {
            return new TicketingResource(true, 'Data Ticketing Berhasil Disimpan!', $ticketing);
        }

        return new TicketingResource(false, 'Data Ticketing Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $ticketing = Ticketings::whereId($id)->first();

        if($ticketing)
        {
            return new TicketingResource(true, 'Detail Data Ticketing!', $ticketing);
        }

        return new TicketingResource(false, 'Detail Data Ticketing Tidak Ditemukan!', null);
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

        $ticketing = Ticketings::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $ticketing->name = strtoupper($request->name);
        $ticketing->company_id = $user->company_id;
        $ticketing->updated_id = $user->id;
        $ticketing->is_active = $request->is_active;

        $ticketing->save();


        if($ticketing)
        {
            return new TicketingResource(true, 'Data Ticketing Berhasil Diupdate!', $ticketing);
        }

        return new TicketingResource(false, 'Data Ticketing Gagal Diupdate!', null);
    }

    public function destroy(Ticketings $ticketing)
    {
        if($ticketing->delete())
        {
            return new TicketingResource(true, 'Data Ticketing Berhasil Dihapus!', null);
        }

        return new TicketingResource(false, 'Data Ticketing Gagal Dihapus!', null);
    }

    public function all()
    {
        $ticketings = Ticketings::latest()->get();

        return new TicketingResource(true, 'List Data Ticketing', $ticketings);
    }
}
