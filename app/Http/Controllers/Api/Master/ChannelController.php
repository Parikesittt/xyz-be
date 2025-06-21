<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Channels;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChannelController extends Controller
{

    public function index()
    {
        $channel = Channels::get();

        return new ChannelResource(true, 'List data channel', $channel);
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

        $user = auth()->guard('api')->user();

        $channel = new Channels();
        $channel->code = $request->code;
        $channel->name = $request->name;
        $channel->company_id = $user->company_id;
        $channel->saved_id = $user->id;
        $channel->is_active = $request->is_active;
        $channel->save();

        if($channel)
        {
            return new ChannelResource(true, 'Data channel Berhasil Disimpan!', $channel);
        }

        return new ChannelResource(false, 'Data channel Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $channel = Channels::whereId($id)->first();

        if($channel)
        {
            return new ChannelResource(true, 'Detail Data channel!', $channel);
        }

        return new ChannelResource(false, 'Detail Data channel Tidak Ditemukan!', null);
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

        $user = auth()->guard('api')->user();

        $channel = Channels::whereId($id)->first();

        $channel->code = $request->code;
        $channel->name = $request->name;
        $channel->company_id = $user->company_id;
        $channel->saved_id = $user->id;
        $channel->is_active = $request->is_active;
        $channel->save();


        if($channel)
        {
            return new ChannelResource(true, 'Data channel Berhasil Diupdate!', $channel);
        }

        return new ChannelResource(false, 'Data channel Gagal Diupdate!', null);
    }

    public function destroy(Channels $channel)
    {
        if($channel->delete())
        {
            return new ChannelResource(true, 'Data channel Berhasil Dihapus!', null);
        }

        return new ChannelResource(false, 'Data channel Gagal Dihapus!', null);
    }

    public function all()
    {
        $Channels = Channels::latest()->get();

        return new ChannelResource(true, 'List Data channel', $Channels);
    }
}
