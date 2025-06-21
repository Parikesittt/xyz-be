<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Financial_statement_rows;
use App\Http\Controllers\Controller;
use App\Http\Resources\FinancialStatementRowResource;
use App\Models\Financial_statement_row_item_totals;
use App\Models\Financial_statement_row_items;
use App\Models\Itemcategorys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FinancialStatementRowController extends Controller
{
    public function index()
    {
        $facilities = Financial_statement_rows::when(request()->search, function ($facilities) {
            $facilities = $facilities->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        $facilities->appends(['search' => request()->search]);

        return new FinancialStatementRowResource(true, 'List data Financial Statement Row', $facilities);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fs_id'  => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $financial = new Financial_statement_rows();
        $financial->financial_statement_id = $request->fs_id;
        $financial->save();

        if($financial)
        {
            return new FinancialStatementRowResource(true, 'Data financial Berhasil Disimpan!', $financial);
        }

        return new FinancialStatementRowResource(false, 'Data Facility Gagal Disimpan!', null);
    }

    public function show($id)
    {
        $financial = Financial_statement_rows::whereId($id)->with('financial_statement_row_items')->first();

        if($financial)
        {
            return new FinancialStatementRowResource(true, 'Detail Data financial!', $financial);
        }

        return new FinancialStatementRowResource(false, 'Detail Data Financial Statement Row Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'fs_id'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $financial = Financial_statement_rows::whereId($id)->first();
        $user = auth()->guard('api')->user();

        $financial->financial_statement_id = $request->fs_id;
        $financial->save();

        if($request->financial_statement_row_items){
            $items = is_string($request->financial_statement_row_items) ? json_decode($request->financial_statement_row_items, true) : $request->financial_statement_row_items;
            if (!is_array($items)) {
                Log::error('office_cost_item is not an array:', ['data' => $items]);
                return response()->json(['error' => 'Invalid format for office_cost_item'], 422);
            }

            foreach ($items as $index => $item) {
                if(!empty($item['fsr_item_id'])){
                    $fsr_item = Financial_statement_row_items::whereId($item['fsr_item_id'])->first();
                    $fsr_item->financial_statement_row_id = $financial->id;

                    $fsr_item->account_name = $item['name'];
                    $fsr_item->account_type = $item['accountType'] ?? null;
                    $fsr_item->column_position = $item['position'] ?? null;
                    $fsr_item->balance = $item['balance'] ?? null;
                    $fsr_item->style_bold = $item['style_bold'] ?? null;
                    $fsr_item->style_underline = $item['style_underline'] ?? null;
                    $fsr_item->style_paragraf = $item['style_paragraf'] ?? null;
                    $fsr_item->show_account = $item['show_account'] ?? null;
                    $fsr_item->position = $index;
                    $fsr_item->formula_list = $item['formula_list'] ?? null;
                    $fsr_item->invert_sign = $item['invert_sign'] ?? null;
                    if($item['ledger_id']){
                        $fsr_item->ledger_id = $item['ledger_id'];
                        $fsr_item->ledger_account = $item['code'];
                    }
                    $fsr_item->save();
                    // dd($price_list_item);
                    if($fsr_item->formula_list == 1){
                        $financial_statement_row_item_total = Financial_statement_row_item_totals::where('financial_statement_row_item_id', $fsr_item['id'])->first();
                    }
                }else {
                    $fsr_item = new Financial_statement_row_items();
                    $fsr_item->financial_statement_row_id = $financial->id;
                    $fsr_item->account_name = $item['name'];
                    $fsr_item->account_type = $item['accountType'] ?? null;
                    $fsr_item->column_position = $item['position'] ?? null;
                    $fsr_item->balance = $item['balance'] ?? null;
                    $fsr_item->style_bold = $item['style_bold'] ?? null;
                    $fsr_item->style_underline = $item['style_underline'] ?? null;
                    $fsr_item->style_paragraf = $item['style_paragraf'] ?? null;
                    $fsr_item->show_account = $item['show_account'] ?? null;
                    $fsr_item->position = $index;
                    $fsr_item->formula_list = $item['formula_list'] ?? null;
                    $fsr_item->invert_sign = $item['invert_sign'] ?? null;
                    if($item['code']){
                        $fsr_item->ledger_id = $item['ledger_id'];
                        $fsr_item->ledger_account = $item['code'];
                    }
                    $fsr_item->save();
                    // dd($price_list_item);
                    if($fsr_item->formula_list == 1){
                        $financial_statement_row_item_total = Financial_statement_row_item_totals::where('financial_statement_row_item_id', $fsr_item['id'])->first();
                    }
                }
            }
        }


        if($financial)
        {
            return new FinancialStatementRowResource(true, 'Data Financial Statement Row Berhasil Diupdate!', $financial);
        }

        return new FinancialStatementRowResource(false, 'Data Financial Statement Row Gagal Diupdate!', null);
    }

    public function destroy(Financial_statement_rows $financial)
    {
        if($financial->delete())
        {
            return new FinancialStatementRowResource(true, 'Data Financial Statement Row Berhasil Dihapus!', null);
        }

        return new FinancialStatementRowResource(false, 'Data Financial Statement Row Gagal Dihapus!', null);
    }

    public function all()
    {
        $fsrs = Financial_statement_rows::latest()->get();

        return new FinancialStatementRowResource(true, 'List Data Financial Statement Row', $fsrs);
    }

    public function rowItemDestroy($fsr_item_id){
        $data = Financial_statement_row_items::whereId($fsr_item_id)->first();

        if($data->delete()){
            return new FinancialStatementRowResource(true, 'Data item berhasil dihapus', null);
        }

        return new FinancialStatementRowResource(false, 'Data item gagal dihapus', null);
    }
}
