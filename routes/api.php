<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);
Route::post('/refresh', [LoginController::class, 'refresh']);

Route::middleware(['token.from.cookie', 'auth:api'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
    Route::get('/check', [LoginController::class, 'check']);

    Route::prefix('/admin')->group(function () {
        Route::get('/user/all', [App\Http\Controllers\Api\Admin\UserController::class, 'all'])
        ->middleware('permission:master_user.index');
        Route::get('/user/index', [App\Http\Controllers\Api\Admin\UserController::class, 'index'])
        ->middleware('permission:master_user.index');
        Route::post('/user/create', [App\Http\Controllers\Api\Admin\UserController::class, 'create'])
        ->middleware('permission:master_user.create');
        Route::put('/user/update/{id}', [App\Http\Controllers\Api\Admin\UserController::class, 'update'])
        ->middleware('permission:master_user.edit');
        Route::apiResource('/user', App\Http\Controllers\Api\Admin\UserController::class)
        ->middleware('permission:master_user.index|master_user.create|master_user.edit|master_user.delete');

        Route::get('/role/all', [App\Http\Controllers\Api\Admin\RoleController::class, 'all'])
        ->middleware('permission:role.index');
        Route::get('/role/index', [App\Http\Controllers\Api\Admin\RoleController::class, 'index'])
        ->middleware('permission:role.index');
        Route::post('/role/create', [App\Http\Controllers\Api\Admin\RoleController::class, 'create'])
        ->middleware('permission:role.create');
        Route::apiResource('/role', App\Http\Controllers\Api\Admin\RoleController::class)
        ->middleware('permission:role.index|role.create|role.edit|role.delete');

        Route::get('/permission/all', [App\Http\Controllers\Api\Admin\PermissionController::class, 'all'])
        ->middleware('permission:role.index');
        Route::apiResource('/permission', App\Http\Controllers\Api\Admin\PermissionController::class)
        ->middleware('permission:role.index');

    });

    Route::prefix('/master')->group(function () {

        Route::get('/company/all', [App\Http\Controllers\Api\Master\CompanyController::class, 'all'])
        ->middleware('permission:master_company.index');
        Route::get('/company/index', [App\Http\Controllers\Api\Master\CompanyController::class, 'index'])
        ->middleware('permission:master_company.index');
        Route::post('/company/create', [App\Http\Controllers\Api\Master\CompanyController::class, 'create'])
        ->middleware('permission:master_company.create');
        Route::put('/company/update/{id}', [App\Http\Controllers\Api\Master\CompanyController::class, 'update'])
        ->middleware('permission:master_company.edit');
        Route::apiResource('/company', App\Http\Controllers\Api\Master\CompanyController::class)
        ->middleware('permission:master_company.index|master_company.create|master_company.edit|master_company.delete');

        Route::get('/city/all', [App\Http\Controllers\Api\Master\CityController::class, 'all'])
        ->middleware('permission:master_city.index');
        Route::get('/city', [App\Http\Controllers\Api\Master\CityController::class, 'index'])
        ->middleware('permission:master_city.index');
        Route::post('/city/create', [App\Http\Controllers\Api\Master\CityController::class, 'create'])
        ->middleware('permission:master_city.create');
        Route::put('/city/update/{id}', [App\Http\Controllers\Api\Master\CityController::class, 'update'])
        ->middleware('permission:master_city.edit');
        Route::apiResource('/city', App\Http\Controllers\Api\Master\CityController::class)
        ->middleware('permission:master_city.index|master_city.create|master_city.edit|master_city.delete');

        Route::get('/location/all', [App\Http\Controllers\Api\Master\LocationController::class, 'all'])
        ->middleware('permission:location.index');
        Route::get('/location', [App\Http\Controllers\Api\Master\LocationController::class, 'index'])
        ->middleware('permission:location.index');
        Route::post('/location/create', [App\Http\Controllers\Api\Master\LocationController::class, 'create'])
        ->middleware('permission:location.create');
        Route::put('/location/update/{id}', [App\Http\Controllers\Api\Master\LocationController::class, 'update'])
        ->middleware('permission:location.edit');
        Route::apiResource('/location', App\Http\Controllers\Api\Master\LocationController::class)
        ->middleware('permission:location.index|location.create|location.edit|location.delete');

        Route::get('/branch/all', [App\Http\Controllers\Api\Master\BranchController::class, 'all'])
        ->middleware('permission:master_branch.index');
        Route::get('/branch', [App\Http\Controllers\Api\Master\BranchController::class, 'index'])
        ->middleware('permission:master_branch.index');
        Route::post('/branch/create', [App\Http\Controllers\Api\Master\BranchController::class, 'create'])
        ->middleware('permission:master_branch.create');
        Route::put('/branch/update/{id}', [App\Http\Controllers\Api\Master\BranchController::class, 'update'])
        ->middleware('permission:master_branch.edit');
        Route::apiResource('/branch', App\Http\Controllers\Api\Master\BranchController::class)
        ->middleware('permission:master_branch.index|master_branch.create|master_branch.edit|master_branch.delete');

        Route::get('/item_sub/all', [App\Http\Controllers\Api\Master\ItemSubcategoryController::class, 'all'])
        ->middleware('permission:master_item_subcategory.index');
        Route::get('/item_sub', [App\Http\Controllers\Api\Master\ItemSubcategoryController::class, 'index'])
        ->middleware('permission:master_item_subcategory.index');
        Route::post('/item_sub/create', [App\Http\Controllers\Api\Master\ItemSubcategoryController::class, 'create'])
        ->middleware('permission:master_item_subcategory.create');
        Route::put('/item_sub/update/{id}', [App\Http\Controllers\Api\Master\ItemSubcategoryController::class, 'update'])
        ->middleware('permission:master_item_subcategory.edit');
        Route::apiResource('/item_sub', App\Http\Controllers\Api\Master\ItemSubcategoryController::class)
        ->middleware('permission:master_item_subcategory.index|master_item_subcategory.create|master_item_subcategory.edit|master_item_subcategory.delete');

        Route::get('/warehouse/all', [App\Http\Controllers\Api\Master\WarehouseController::class, 'all'])
        ->middleware('permission:warehouse.index');
        Route::get('/warehouse', [App\Http\Controllers\Api\Master\WarehouseController::class, 'index'])
        ->middleware('permission:warehouse.index');
        Route::get('/warehouse/stores_central', [App\Http\Controllers\Api\Master\WarehouseController::class, 'stores_central'])
        ->middleware('permission:warehouse.index');
        Route::post('/warehouse/create', [App\Http\Controllers\Api\Master\WarehouseController::class, 'create'])
        ->middleware('permission:warehouse.create');
        Route::put('/warehouse/update/{id}', [App\Http\Controllers\Api\Master\WarehouseController::class, 'update'])
        ->middleware('permission:warehouse.edit');
        Route::apiResource('/warehouse', App\Http\Controllers\Api\Master\WarehouseController::class)
        ->middleware('permission:warehouse.index|warehouse.create|warehouse.edit|warehouse.delete');

        Route::get('/item_super_group/all', [App\Http\Controllers\Api\Master\ItemSuperGroupController::class, 'all'])
        ->middleware('permission:master_item_super_group.index');
        Route::get('/item_super_group', [App\Http\Controllers\Api\Master\ItemSuperGroupController::class, 'index'])
        ->middleware('permission:master_item_super_group.index');
        Route::post('/item_super_group/create', [App\Http\Controllers\Api\Master\ItemSuperGroupController::class, 'create'])
        ->middleware('permission:master_item_super_group.create');
        Route::put('/item_super_group/update/{id}', [App\Http\Controllers\Api\Master\ItemSuperGroupController::class, 'update'])
        ->middleware('permission:master_item_super_group.edit');
        Route::apiResource('/item_super_group', App\Http\Controllers\Api\Master\ItemSuperGroupController::class)
        ->middleware('permission:master_item_super_group.index|master_item_super_group.create|master_item_super_group.edit|master_item_super_group.delete');

        Route::get('/item_group/all', [App\Http\Controllers\Api\Master\ItemGroupController::class, 'all'])
        ->middleware('permission:master_item_group.index');
        Route::get('/item_group', [App\Http\Controllers\Api\Master\ItemGroupController::class, 'index'])
        ->middleware('permission:master_item_group.index');
        Route::post('/item_group/create', [App\Http\Controllers\Api\Master\ItemGroupController::class, 'create'])
        ->middleware('permission:master_item_group.create');
        Route::put('/item_group/update/{id}', [App\Http\Controllers\Api\Master\ItemGroupController::class, 'update'])
        ->middleware('permission:master_item_group.edit');
        Route::apiResource('/item_group', App\Http\Controllers\Api\Master\ItemGroupController::class)
        ->middleware('permission:master_item_group.index|master_item_group.create|master_item_group.edit|master_item_group.delete');

        Route::get('/subcategory/all', [App\Http\Controllers\Api\Master\SubcategoryController::class, 'all'])
        ->middleware('permission:master_item_subcategory.index');
        Route::get('/subcategory', [App\Http\Controllers\Api\Master\SubcategoryController::class, 'index'])
        ->middleware('permission:master_item_subcategory.index');
        Route::post('/subcategory/create', [App\Http\Controllers\Api\Master\SubcategoryController::class, 'create'])
        ->middleware('permission:master_item_subcategory.create');
        Route::put('/subcategory/update/{id}', [App\Http\Controllers\Api\Master\SubcategoryController::class, 'update'])
        ->middleware('permission:master_item_subcategory.edit');
        Route::apiResource('/subcategory', App\Http\Controllers\Api\Master\SubcategoryController::class)
        ->middleware('permission:master_item_subcategory.index|master_item_subcategory.create|master_item_subcategory.edit|master_item_subcategory.delete');

        Route::get('/unit/all', [App\Http\Controllers\Api\Master\UnitController::class, 'all'])
        ->middleware('permission:master_unit.index');
        Route::get('/unit', [App\Http\Controllers\Api\Master\UnitController::class, 'index'])
        ->middleware('permission:master_unit.index');
        Route::post('/unit/create', [App\Http\Controllers\Api\Master\UnitController::class, 'create'])
        ->middleware('permission:master_unit.create');
        Route::put('/unit/update/{id}', [App\Http\Controllers\Api\Master\UnitController::class, 'update'])
        ->middleware('permission:master_unit.edit');
        Route::apiResource('/unit', App\Http\Controllers\Api\Master\UnitController::class)
        ->middleware('permission:master_unit.index|master_unit.create|master_unit.edit|master_unit.delete');

        Route::get('/item', [App\Http\Controllers\Api\Master\ItemController::class, 'index'])
        ->middleware('permission:master_item.index');
        Route::post('/item/create', [App\Http\Controllers\Api\Master\ItemController::class, 'store'])
        ->middleware('permission:master_item.create');
        Route::put('/item/update/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'update'])
        ->middleware('permission:master_item.edit');
        Route::put('/item/post/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'post'])
        ->middleware('permission:master_item.edit');
        Route::get('/item/getItemGroup/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'getItemGroup'])
        ->middleware('permission:master_item.index');
        Route::get('/item/getItemSubgroup/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'getItemSubgroup'])
        ->middleware('permission:master_item.index');
        Route::get('/item/posteds', [App\Http\Controllers\Api\Master\ItemController::class, 'posteds'])
        ->middleware('permission:master_item.index');
        Route::get('/item/posted/office/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'postedOffice'])
        ->middleware('permission:master_item.index');
        Route::post('/item/saveProductCode/{item_code}', [App\Http\Controllers\Api\Master\ItemController::class, 'saveProductCode'])
        ->middleware('permission:master_item.index');
        Route::get('/item/getProductCode/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'getProductCode'])
        ->middleware('permission:master_item.index');
        Route::put('/item/updateProductCode/{id}', [App\Http\Controllers\Api\Master\ItemController::class, 'updateProductCode'])
        ->middleware('permission:master_item.index');
        Route::post('/item/postOfficePart/{id}/{item_code}', [App\Http\Controllers\Api\Master\ItemController::class, 'postOfficePart'])
        ->middleware('permission:master_item.index');
        Route::apiResource('/item', App\Http\Controllers\Api\Master\ItemController::class)
        ->middleware('permission:master_item.index|master_item.create|master_item.edit|master_item.delete');
        Route::get('/export/master-item', [App\Http\Controllers\Api\Export\MasterItemController::class, 'export']);

        Route::get('/bankaccount', [App\Http\Controllers\Api\Master\BankAccountController::class, 'index'])
        ->middleware('permission:master_bank.index');
        Route::post('/bankaccount/create', [App\Http\Controllers\Api\Master\BankAccountController::class, 'create'])
        ->middleware('permission:master_bank.create');
        Route::put('/bankaccount/update/{id}', [App\Http\Controllers\Api\Master\BankAccountController::class, 'update'])
        ->middleware('permission:master_bank.edit');
        Route::apiResource('/bankaccount', App\Http\Controllers\Api\Master\BankAccountController::class)
        ->middleware('permission:master_bank.index|master_bank.create|master_bank.edit|master_bank.delete');
        Route::get('/technician', [App\Http\Controllers\Api\Master\TechnicianController::class, 'index'])
        ->middleware('permission:technician.index');
        Route::post('/technician/create', [App\Http\Controllers\Api\Master\TechnicianController::class, 'create'])
        ->middleware('permission:technician.create');
        Route::put('/technician/update/{id}', [App\Http\Controllers\Api\Master\TechnicianController::class, 'update'])
        ->middleware('permission:technician.edit');
        Route::apiResource('/technician', App\Http\Controllers\Api\Master\TechnicianController::class)
        ->middleware('permission:technician.index|technician.create|technician.edit|technician.delete');

        Route::get('/business_type/all', [App\Http\Controllers\Api\Master\BusinessTypeController::class, 'all'])
        ->middleware('permission:master_business_type.index');
        Route::get('/business_type', [App\Http\Controllers\Api\Master\BusinessTypeController::class, 'index'])
        ->middleware('permission:master_business_type.index');
        Route::post('/business_type/create', [App\Http\Controllers\Api\Master\BusinessTypeController::class, 'create'])
        ->middleware('permission:master_business_type.create');
        Route::put('/business_type/update/{id}', [App\Http\Controllers\Api\Master\BusinessTypeController::class, 'update'])
        ->middleware('permission:master_business_type.edit');
        Route::apiResource('/business_type', App\Http\Controllers\Api\Master\BusinessTypeController::class)
        ->middleware('permission:master_business_type.index|master_business_type.create|master_business_type.edit|master_business_type.delete');
        Route::get('/facility', [App\Http\Controllers\Api\Master\FacilityController::class, 'index'])
        ->middleware('permission:master_facility.index');
        Route::post('/facility/create', [App\Http\Controllers\Api\Master\FacilityController::class, 'create'])
        ->middleware('permission:master_facility.create');
        Route::put('/facility/update/{id}', [App\Http\Controllers\Api\Master\FacilityController::class, 'update'])
        ->middleware('permission:master_facility.edit');
        Route::apiResource('/facility', App\Http\Controllers\Api\Master\FacilityController::class)
        ->middleware('permission:master_facility.index|master_facility.create|master_facility.edit|master_facility.delete');
        Route::get('/ticketing', [App\Http\Controllers\Api\Master\TicketingController::class, 'index'])
        ->middleware('permission:master_ticketing.index');
        Route::post('/ticketing/create', [App\Http\Controllers\Api\Master\TicketingController::class, 'create'])
        ->middleware('permission:master_ticketing.create');
        Route::put('/ticketing/update/{id}', [App\Http\Controllers\Api\Master\TicketingController::class, 'update'])
        ->middleware('permission:master_ticketing.edit');
        Route::apiResource('/ticketing', App\Http\Controllers\Api\Master\TicketingController::class)
        ->middleware('permission:master_ticketing.index|master_ticketing.create|master_ticketing.edit|master_ticketing.delete');

        Route::apiResource('/currency', App\Http\Controllers\Api\Master\CurrencyController::class);
        Route::apiResource('/customer_head_group', App\Http\Controllers\Api\Master\CustomerHeadGroupController::class);
        Route::apiResource('/customer_department', App\Http\Controllers\Api\Master\CustomerDepartmentsController::class);
        Route::apiResource('/customer_area', App\Http\Controllers\Api\Master\CustomerAreaController::class);
        Route::apiResource('/customer_market', App\Http\Controllers\Api\Master\CustomerMarketController::class);
        Route::apiResource('/channel', App\Http\Controllers\Api\Master\ChannelController::class);


        Route::get('/customer/all', [App\Http\Controllers\Api\Master\CustomerController::class, 'all'])
        ->middleware('permission:customer.index');
        Route::get('/customer/cek_location_cust', [App\Http\Controllers\Api\Master\CustomerController::class, 'cek_location_cust'])
        ->middleware('permission:customer.index');
        Route::get('/customer/customer_group', [App\Http\Controllers\Api\Master\CustomerController::class, 'customer_groups'])
        ->middleware('permission:customer.index');
        Route::apiResource('/customer', App\Http\Controllers\Api\Master\CustomerController::class)
        ->middleware('permission:customer.index|customer.create|customer.edit|customer.delete');

        Route::get('/vendor/all', [App\Http\Controllers\Api\Master\VendorController::class, 'all'])
        ->middleware('permission:master_vendor.index');
        Route::put('/vendor/update/{id}', [App\Http\Controllers\Api\Master\VendorController::class, 'update'])
        ->middleware('permission:master_vendor.edit');
        Route::get('/vendor_groups', [App\Http\Controllers\Api\Master\VendorController::class, 'vendor_groups'])
        ->middleware('permission:master_vendor.index');
        Route::get('/supplier/{accountNum}', [App\Http\Controllers\Api\Master\VendorController::class, 'supplier'])
        ->middleware('permission:master_vendor.index');
        Route::apiResource('/vendor', App\Http\Controllers\Api\Master\VendorController::class)
        ->middleware('permission:master_vendor.index|master_vendor.create|master_vendor.edit|master_vendor.delete');

        Route::get('/office_cost/all', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'all'])
        ->middleware('permission:office_cost.index');
        Route::get('/office_cost/onsite/all', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'onSiteAll'])
        ->middleware('permission:office_cost.index');
        Route::get('/office_cost/onsite', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'onSiteIndex'])
        ->middleware('permission:office_cost.index');
        Route::get('/office_cost/onsite/{id}', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'onSiteShow'])
        ->middleware('permission:office_cost.index');
        Route::post('/office_cost/onsite', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'onSiteStore'])
        ->middleware('permission:office_cost.create');
        Route::put('/office_cost/onsite/update/{id}', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'onSiteUpdate'])
        ->middleware('permission:office_cost.edit');
        Route::put('/office_cost/update/{id}', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'update'])
        ->middleware('permission:office_cost.edit');
        Route::get('/office_cost/onsite_area', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'onSiteArea'])
        ->middleware('permission:office_cost.index');
        Route::apiResource('/office_cost', App\Http\Controllers\Api\Master\OfficeCostController::class)
        ->middleware('permission:office_cost.index|office_cost.create|office_cost.edit|office_cost.delete');

        Route::get('/office_price_list/all', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'all'])
        ->middleware('permission:office_price_list.index');
        Route::get('/office_price_list/add_item/{part_for}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'itemDetailPart'])
        ->middleware('permission:office_price_list.index');
        Route::get('/office_price_list/search', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'searchPriceList'])
        ->middleware('permission:office_price_list.index');
        Route::put('/office_price_list/update/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'update'])
        ->middleware('permission:office_price_list.edit');
        Route::post('/office_price_list/deletePriceListItem/{id}/{pl_id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'destroyPriceListItem'])
        ->middleware('permission:office_price_list.delete');
        Route::get('/office_reset', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeResetIndex'])
        ->middleware('permission:office_price_list.index');
        Route::get('/office_reset/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeResetShow'])
        ->middleware('permission:office_price_list.index');
        Route::put('/office_reset/update/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeResetUpdate'])
        ->middleware('permission:office_price_list.edit');
        Route::post('/office_reset', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeResetStore'])
        ->middleware('permission:office_price_list.delete');
        Route::get('/office_consumable', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeConsumableIndex'])
        ->middleware('permission:office_price_list.index');
        Route::get('/office_consumable/item', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeConsumableItem'])
        ->middleware('permission:office_price_list.index');
        Route::get('/office_consumable/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeConsumableShow'])
        ->middleware('permission:office_price_list.index');
        Route::put('/office_consumable/update/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeConsumableUpdate'])
        ->middleware('permission:office_price_list.edit');
        Route::post('/office_consumable', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'officeConsumableStore'])
        ->middleware('permission:office_price_list.delete');
        Route::get('/price_list_so', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'priceListSOIndex'])
        ->middleware('permission:office_price_list.index');
        Route::get('/price_list_so/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'priceListSOShow'])
        ->middleware('permission:office_price_list.index');
        Route::put('/price_list_so/update/{id}', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'priceListSOUpdate'])
        ->middleware('permission:office_price_list.edit');
        Route::post('/price_list_so', [App\Http\Controllers\Api\Master\OfficePriceListController::class, 'priceListSOStore'])
        ->middleware('permission:office_price_list.delete');
        Route::apiResource('/office_price_list', App\Http\Controllers\Api\Master\OfficePriceListController::class)
        ->middleware('permission:office_price_list.index|office_price_list.create|office_price_list.edit|office_price_list.delete');

        Route::get('/financial_statement_row/all', [App\Http\Controllers\Api\Master\FinancialStatementRowController::class, 'all'])
        ->middleware('permission:fsr.index');
        Route::put('/financial_statement_row/update/{id}', [App\Http\Controllers\Api\Master\FinancialStatementRowController::class, 'update'])
        ->middleware('permission:fsr.index');
        Route::delete('/financial_statement_row/deleteItem/{fsr_item_id}', [App\Http\Controllers\Api\Master\FinancialStatementRowController::class, 'rowItemDestroy'])
        ->middleware('permission:fsr.delete');
        Route::apiResource('/financial_statement_row', App\Http\Controllers\Api\Master\FinancialStatementRowController::class)
        ->middleware('permission:fsr.index|fsr.create|fsr.edit|fsr.delete');

        Route::get('/financial_statement/all', [App\Http\Controllers\Api\Master\FinancialStatementController::class, 'all'])
        ->middleware('permission:fs.index');
        Route::apiResource('/financial_statement', App\Http\Controllers\Api\Master\FinancialStatementController::class)
        ->middleware('permission:fs.index|fs.create|fs.edit|fs.delete');

        Route::get('/ledger/all', [App\Http\Controllers\Api\Master\LedgerController::class, 'all'])
        ->middleware('permission:ledger.index');
        Route::get('/ledger/cekAccount/{account}', [App\Http\Controllers\Api\Master\LedgerController::class, 'cekAccount'])
        ->middleware('permission:ledger.index');
        Route::put('/ledger/update/{id}', [App\Http\Controllers\Api\Master\LedgerController::class, 'update'])
        ->middleware('permission:ledger.edit');
        Route::apiResource('/ledger', App\Http\Controllers\Api\Master\LedgerController::class)
        ->middleware('permission:ledger.index|ledger.create|ledger.edit|ledger.delete');

        Route::get('/item_sales_tax/all', [App\Http\Controllers\Api\Master\ItemSalesTaxController::class, 'all'])
        ->middleware('permission:item_sales_tax.index');
        Route::apiResource('/item_sales_tax', App\Http\Controllers\Api\Master\ItemSalesTaxController::class)
        ->middleware('permission:item_sales_tax.index|item_sales_tax.create|item_sales_tax.edit|item_sales_tax.delete');
    });

    Route::get('/warehouse/{flag}/{warranty}', [App\Http\Controllers\Api\Master\WarehouseController::class, 'withFlagandWarranty'])
    ->middleware('permission:warehouse.index');

    Route::get('/warehouseWarranty/{is_branch}/{location_id}', [App\Http\Controllers\Api\Master\WarehouseController::class, 'warehouseWarranty'])
    ->middleware('permission:warehouse.index');

    Route::put('/office_cs/saveRMA/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'saveRMA'])->where('id', '[0-9]+')
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/detailCs/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'detailCs'])->where('id', '[0-9]+')
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/detailParts/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'detailParts'])->where('id', '[0-9]+')
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/statusPickup/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'statusPickup'])->where('id', '[0-9]+')
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/statusTransferPart/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'statusTransferPart'])->where('id', '[0-9]+')
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/office_cs_item_type/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'office_cs_item_types'])->where('id', '[0-9]+')
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/item/{id}/{item_id}', [App\Http\Controllers\Api\OfficeCsController::class, 'historyItem'])
    ->middleware('permission:office_cs.index|office_cs.edit');

    Route::get('/office_cs/show/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'show'])
    ->middleware('permission:office_cs.index|office_cs.edit');

    Route::put('/office_cs/update/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'update'])
    ->middleware('permission:office_cs.index|office_cs.edit');

    Route::get('/office_cs/{status}', [App\Http\Controllers\Api\OfficeCsController::class, 'filters'])
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/{status}/{inc_source}', [App\Http\Controllers\Api\OfficeCsController::class, 'filterwithSource'])
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/workSelectParts', [App\Http\Controllers\Api\OfficeCsController::class, 'workSelectPart'])
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/saveQty/{id}/{dex}', [App\Http\Controllers\Api\OfficeCsController::class, 'saveQty'])
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/saveStock/{id}/{dex}', [App\Http\Controllers\Api\OfficeCsController::class, 'saveStock'])
    ->middleware('permission:office_cs.index');

    Route::put('/office_cs/processParts/{id}/{type}', [App\Http\Controllers\Api\OfficeCsController::class, 'processParts'])
    ->middleware('permission:office_cs.index');

    Route::post('/office_cs/email', [App\Http\Controllers\Api\OfficeCsController::class, 'email'])
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/approveCust', [App\Http\Controllers\Api\OfficeCsController::class, 'approveCust'])
    ->middleware('permission:office_cs.index');

    Route::post('/office_cs/approveCustWithId/{id}/{cancel_type}', [App\Http\Controllers\Api\OfficeCsController::class, 'approveCustWithId'])
    ->middleware('permission:office_cs.index');

    Route::get('/office_cs/processInvoiceDP/{id}', [App\Http\Controllers\Api\OfficeCsController::class, 'processInvoiceDP'])
    ->middleware('permission:office_cs.index');

    Route::put('/office_cs_item_types/{id}/{status}/{cs_id}/{type}/{item_code}', [App\Http\Controllers\Api\OfficeCsController::class, 'officeCsItemTypes'])
    ->middleware('permission:office_cs.edit');

    Route::get('/office_work_select_part/{item_code}/{cs_item_id}', [App\Http\Controllers\Api\OfficeCsController::class, 'officeWorkParts'])
    ->middleware('permission:office_cs.edit');

    Route::get('/office_consumable_items/{cs_item_id}/{item_id}', [App\Http\Controllers\Api\OfficeCsController::class, 'officeWorkConsumables'])
    ->middleware('permission:office_cs.edit');

    Route::put('/office_cs/saveNote/{cs_id}', [App\Http\Controllers\Api\OfficeCsController::class, 'saveNoteCsItem'])
    ->middleware('permission:office_cs.edit');

    Route::post('/office_cs/saveStatusStock/{id}/{dex}', [App\Http\Controllers\Api\OfficeCsController::class, 'saveStatusStock'])
    ->middleware('permission:office_cs.edit');

    Route::post('/office_cs/processPartsEin/{id}/{type}', [App\Http\Controllers\Api\OfficeCsController::class, 'processPartsEin'])
    ->middleware('permission:office_cs.edit');

    Route::apiResource('/office_cs', App\Http\Controllers\Api\OfficeCsController::class)
    ->middleware('permission:office_cs.index|office_cs.create|office_cs.edit|office_cs.delete');

    Route::get('/cek_office_cost_items', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'cekOfficeCostItem'])
    ->middleware('permission:office_cost.index');
    Route::get('/office_customer_items/{customer_id}', [App\Http\Controllers\Api\Master\OfficeCostController::class, 'getCustomerItem'])
    ->middleware('permission:office_cost.index');

    Route::get('/technician', [App\Http\Controllers\Api\Master\TechnicianController::class, 'index'])
    ->middleware('permission:technician.index');


    Route::get('/generate-pdf-quotation/{id}', [App\Http\Controllers\PDFController::class, 'makeCustomerServicePart']);
    Route::get('/generate-pdf-coll-slip/{id}', [App\Http\Controllers\PDFController::class, 'makeCustomerService']);

});

