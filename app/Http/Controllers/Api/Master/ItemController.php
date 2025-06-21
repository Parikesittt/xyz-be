<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\Itemcategorys;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemGroupResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Office_part_items;
use App\Models\Itemgroups;
use App\Models\Subcategorys;
use App\Models\Units;
use App\Models\Items;
use App\Models\Product_codes;
use App\Models\Sr_unit_converts;
use App\Models\Unit_convert_globals;
use App\Models\Unit_converts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = DB::table('item')
                ->select(
                    'item.id',
                    'item.item_id',
                    'item.code',
                    'item.name',
                    'item.unit',
                    'item.is_active',
                    'item.itemgroup_id',
                    'item.itemcategory_id',
                    'item.subcategory_id',
                    'item.item_subcategory_id',
                    DB::raw('(select name from item_subcategory where id = item.item_subcategory_id) as item_subcategory_name'),
                    'item.type_item_office',
                    'item.category_office',
                    'item.remark',
                    'item.is_posted',
                    DB::raw('office_product_code.product_code as product_code')
                )
                ->leftJoin('office_product_code', 'office_product_code.item_code', '=', 'item.id')
                ->when(request()->search, function ($items) {
                    $items = $items->where('name', 'like', '%' . request()->search . '%');
                })
                ->orderBy('item.is_posted')
                ->orderBy('item.code', 'asc')
                ->paginate(10);

        return new ItemResource(true, 'List data Item', $items);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'itemcategory_id' => 'required',
            'subcategory_id' => 'required',
            'is_active' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }


        $user = auth()->guard('api')->user();
        $itemcategory = Itemcategorys::whereId($request->itemcategory_id)->first();
        $unit = Units::whereId($request->unit_id)->first();
        $subcategory = Subcategorys::whereId($request->subcategory_id)->first();

        $item = new Items();
        $item->name = $request->name;
        $item->user_id = $user->id;
        $item->itemgroupnumber = $itemcategory->code ?? '';
        $item->unit = $unit->unit ?? '';
        $item->unit_id = $request->unit_id ?? '';
        $item->itemgroup_id = $request->itemgroup_id ?? '';
        $item->itemcategory_id = $request->itemcategory_id ?? '';
        $item->subcategory_id = $request->subcategory_id ?? '';
        $item->item_subcategory_id = $request->item_subcategory_id ?? '';
        $item->item_status = $request->item_status ?? 0;
        $item->base_price = $request->base_price ?? 0;
        $item->item_tax_group = $request->item_tax_group ?? '';
        $item->item_type = $request->item_type ?? '';
        $item->item_sales_tax = $request->item_sales_tax ?? '';
        $item->is_active = $request->is_active ?? '';
        $item->is_posted_sr = $request->is_posted_sr ?? 0;
        $item->item_class = $request->item_class ?? 0;
        $item->masa_item = $request->masa_item ?? 0;
        $item->std_print = $request->std_print ?? 0;
        $item->capacity = $request->capacity ?? 0;
        $item->is_ecommerce = $request->is_ecommerce ?? 0;
        $item->code = Items::getNextCounterId($subcategory->code);
        $item->id = $item->code;
        $item->save();

        if($item)
        {
            return new ItemResource(true, 'Data Item Berhasil Disimpan!', $item);
        }

        return new ItemResource(false, 'Data Item Gagal Disimpan!', null);
    }

    public function show($item_id)
    {
        $item = Items::where('item_id', $item_id)->with('office_part_items')->first();

        if($item)
        {
            return new ItemResource(true, 'Detail Data Item!', $item);
        }

        return new ItemResource(false, 'Detail Data Item Tidak Ditemukan!', null);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $itemgroups = Itemgroups::whereId($request->itemgroup_id)->first();
        $user = auth()->guard('api')->user();

        $item = Items::whereId($id)->first();

        $item->name = $request->name;
        $item->user_update_id = $user->id;
        $item->itemgroupnumber = $itemcategory->code ?? '';
        $item->unit = $unit->unit ?? '';
        $item->unit_id = $request->unit_id ?? '';
        $item->itemgroup_id = $request->itemgroup_id ?? '';
        $item->itemcategory_id = $request->itemcategory_id ?? '';
        $item->subcategory_id = $request->subcategory_id ?? '';
        $item->item_subcategory_id = $request->item_subcategory_id ?? '';
        $item->item_status = $request->item_status ?? 0;
        $item->base_price = $request->base_price ?? 0;
        $item->item_tax_group = $request->item_tax_group ?? '';
        $item->item_type = $request->item_type ?? '';
        $item->item_sales_tax = $request->item_sales_tax ?? '';
        $item->is_active = $request->is_active ?? '';
        $item->is_posted_sr = $request->is_posted_sr ?? 0;
        $item->item_class = $request->item_class ?? 0;
        $item->brand_id = $request->brand_id ?? 0;
        $item->item_brand = $request->item_brand ?? '';
        $item->masa_item = $request->masa_item ?? 0;
        $item->std_print = $request->std_print ?? 0;
        $item->capacity = $request->capacity ?? 0;
        $item->is_ecommerce = $request->is_ecommerce ?? 0;

        $item->is_office = $request->is_office ?? 0;
        $item->type_item_office = $request->type_item_office ?? 0;
        $item->product_code = $request->product_code ?? 0;
        $item->type = $request->type ?? 0;
        $item->category_office = $request->category_office ?? 0;
        $item->part_value = $request->part_value ?? 0;
        $item->remark = $request->remark ?? 0;
        $item->minimum_stock = $request->minimum_stock ?? 0;
        $item->control_item = $request->control_item ?? 0;

        $item->save();


        if($item)
        {
            return new ItemResource(true, 'Data Item Group Berhasil Diupdate!', $item);
        }

        return new ItemResource(false, 'Data Item Group Gagal Diupdate!', null);
    }

    public function postedOffice($id){
        $data = Items::when(request()->search, function ($office_cost) {
            $office_cost = $office_cost->where('name', 'like', '%' . request()->search . '%')
            ->orWhere('code', 'like', '%' . request()->search . '%');
        })
        ->where('is_posted', 1)->where('is_active', 1)->where('type_item_office', $id)
        ->latest()->orderBy('id', 'asc')->paginate(10);

        return new ItemResource(true, 'List item posted office', $data);
    }

    public function importItemExcel(Request $request){
        $data = json_decode($request->input('myModel'), true);
    }

    public function post($id){
        $item = Items::whereId($id)->first();

        $item->is_posted = 1;
        $item->save();

        if($item){
            return new ItemResource(true, 'Item berhasil di posting', $item);
        }
        return new ItemResource(false, 'Item gagal diposting', null);
    }

    public function getProductCode($id){
        $product_code = Product_codes::where('item_code', $id)->latest()->get();

        if($product_code){
            return new ItemResource(true, 'List Product Code: ', $product_code);
        }
        return new ItemResource(false, 'List Product Code tidak ditemukan', null);
    }

    public function saveProductCode (Request $request, $item_code){
        $validator = Validator::make($request->all(), [
            'product_code'  => 'required',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $items = items::where('product_code' ,$item_code)->first();
        $product_code_check = Product_codes::where('product_code', $request->product_code)->first();
        $user = auth()->guard('api')->user();

        if(!$product_code_check){
            $product_code = new Product_codes();
            $product_code->item_code = $item_code;
            $product_code->product_code = $request->product_code;
            $product_code->item_code_unit = $request->item_code_unit ?? 0;
            $product_code->item_name_unit = $request->item_name_unit ?? 0;
            $product_code->is_active = $request->is_active;
            $product_code->company_id = $user->company_id;
            $product_code->saved_id = $user->id;

            if($product_code->product_code != '' && $product_code->product_code != 'undefined'){
                $product_code->save();
            }
        }

        if($product_code){
            return new ItemResource(true, 'Product Code berhasil disimpan', $product_code);
        }

        return new ItemResource(false, 'Product code gagal disimpan', null);
    }

    public function updateProductCode(Request $request, $item_code){
        $validator = Validator::make($request->all(), [
            'product_code'  => 'required',
        ]);

        $items = items::where('product_code' ,$item_code)->first();
        $product_code = Product_codes::where('product_code', $request->product_code)->first();
        $user = auth()->guard('api')->user();

        if($product_code){
            $product_code->item_code = $item_code;
            $product_code->product_code = $request->product_code;
            $product_code->item_code_unit = $request->item_code_unit ?? 0;
            $product_code->item_name_unit = $request->item_name_unit ?? 0;
            $product_code->is_active = $request->is_active;
            $product_code->company_id = $user->company_id;
            $product_code->saved_id = $user->id;

            if($product_code->product_code != '' && $product_code->product_code != 'undefined'){
                $product_code->save();
            }
        }

        if($product_code){
            return new ItemResource(true, 'Product Code berhasil diperbarui', $product_code);
        }

        return new ItemResource(false, 'Product code gagal diperbarui', null);
    }

    public function postOfficePart(Request $request, $item_code, $id){
        $item = Items::where('code', $item_code)->first();

        $office_part_item = new Office_part_items();
        $office_part_item->part_for = $id;
        $office_part_item->item_code = $item_code;
        $office_part_item->is_active = 1;

        if($item){
            $office_part_item->product_code = $item->product_code;
            $office_part_item->item_name = $item->name;
            $office_part_item->item_unit = $item->unit;
        }


        $office_part_item->save();

        if($office_part_item){
            return new ItemResource(true, 'Office part item berhasil disimpan', $office_part_item);
        }

        return new ItemResource(false, 'Office part item gagal disimpan', null);
    }

    public function destroy(Itemcategorys $itemcategory)
    {
        if($itemcategory->delete())
        {
            return new ItemResource(true, 'Data Item Group Berhasil Dihapus!', null);
        }

        return new ItemResource(false, 'Data Item Group Gagal Dihapus!', null);
    }

    public function all()
    {
        $itemcategories = Itemcategorys::latest()->get();

        return new ItemResource(true, 'List Data Item Group', $itemcategories);
    }

    public function getItemGroup($id){
        $itemGroup = Itemcategorys::where('itemgroup_id', $id)->latest()->get();

        return new ItemResource(true, 'List Item Group: ', $itemGroup);
    }

    public function getItemSubgroup($id){
        $itemSubGroup = Subcategorys::where('itemcategory_id', $id)->latest()->get();

        return new ItemResource(true, 'List Item Sub Category: ', $itemSubGroup);
    }

    public function posteds(){
        $itemPosteds = DB::table('item')
                ->select(
                    'item.id',
                    'item.item_id',
                    'item.code',
                    'item.name',
                    'item.unit',
                    'item.is_active',
                    'item.itemgroup_id',
                    'item.itemcategory_id',
                    'item.subcategory_id',
                    'item.item_subcategory_id',
                    DB::raw('(select name from item_subcategory where id = item.item_subcategory_id) as item_subcategory_name'),
                    'item.type_item_office',
                    'item.category_office',
                    'item.remark',
                    'item.is_posted',
                    DB::raw('office_product_code.product_code as product_code')
                )
                ->leftJoin('office_product_code', 'office_product_code.item_code', '=', 'item.id')
                ->when(request()->search, function ($items) {
                    $items = $items->where('name', 'like', '%' . request()->search . '%');
                })
                ->where('is_posted', 1)
                ->orderBy('item.code', 'asc')
                ->paginate(10);

        return new ItemResource(true, 'List Item Posteds: ', $itemPosteds);
    }
}
