<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'home.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'home.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'home.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'home.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'user.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'user.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'user.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'user.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'group.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'group.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'group.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'group.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'route.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'route.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'route.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'route.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'role.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'role.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'role.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'role.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'configuration.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'configuration.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'configuration.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'configuration.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_company.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_company.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_company.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_company.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_city.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_city.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_city.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_city.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_item.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_price.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_price.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_price.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_price.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_all.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_all.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_all.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_all.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pr_iptn.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_iptn.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_iptn.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_iptn.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_item_super_group.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_super_group.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_super_group.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_super_group.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_item_group.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_group.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_group.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_group.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_measurment_unit.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_measurment_unit.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_measurment_unit.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_measurment_unit.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_item_subcategory.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_subcategory.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_subcategory.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_subcategory.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_item_category.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_category.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_category.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_item_category.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_usd.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_usd.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_usd.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_usd.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_location.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_location.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_location.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_location.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'purchase_request.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_request.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_request.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_request.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'purchase_request_all.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_request_all.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_request_all.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_request_all.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pr_non_cash.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_non_cash.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_non_cash.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_non_cash.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pr_cash.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_cash.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_cash.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_cash.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pr_procurement.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_procurement.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_procurement.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_procurement.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'transaction_all.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction_all.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction_all.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction_all.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'transaction.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_receiving.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_receiving.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_receiving.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_receiving.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_transfer_in.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_transfer_in.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_transfer_in.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_transfer_in.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_transfer_out.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_transfer_out.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_transfer_out.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_transfer_out.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_backcharge.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_backcharge.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_backcharge.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_backcharge.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_issued.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_issued.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_issued.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_issued.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_iptn_out.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_iptn_out.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_iptn_out.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_iptn_out.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'trans_iptn_in.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_iptn_in.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_iptn_in.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'trans_iptn_in.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'stock.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'stock.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'stock.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'stock.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'mics.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'mics.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'mics.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'mics.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'closing.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pr_warehouse.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_warehouse.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_warehouse.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pr_warehouse.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'purchase_order.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_order.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_order.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase_order.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'master_vendor.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_vendor.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_vendor.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'master_vendor.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'adjustment.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'adjustment.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'adjustment.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'adjustment.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'transaction_retur.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction_retur.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction_retur.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'transaction_retur.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'packing_list.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'packing_list.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'packing_list.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'packing_list.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'compare.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'compare.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'compare.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'compare.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'dcs.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'dcs.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'dcs.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'dcs.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'meal.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'meal.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'meal.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'meal.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'sales_order.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'sales_order.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'sales_order.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'sales_order.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'dashboard.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'dashboard.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'dashboard.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'dashboard.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'account_payable.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'account_payable.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'account_payable.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'account_payable.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'invoice.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'invoice.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'invoice.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'invoice.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'monitoring.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'monitoring.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'monitoring.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'monitoring.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'ledger.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'ledger.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'ledger.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'ledger.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pnl.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pnl.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pnl.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pnl.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'payroll.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'payroll.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'payroll.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'payroll.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'indirect.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'indirect.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'indirect.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'indirect.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'basic.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'basic.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'basic.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'basic.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'location.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'location.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'location.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'location.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'warehouse.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'warehouse.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'warehouse.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'warehouse.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'mutation.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'mutation.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'mutation.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'mutation.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'purchase.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'purchase.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'accounting.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'accounting.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'accounting.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'accounting.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'report.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'report.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'report.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'report.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'inventory.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'inventory.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'inventory.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'inventory.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'closing_inventory.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing_inventory.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing_inventory.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing_inventory.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'closing_year.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing_year.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing_year.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'closing_year.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'transport.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'transport.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'transport.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'transport.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'driver.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'driver.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'driver.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'driver.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'helper.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'helper.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'helper.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'helper.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'discount.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'discount.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'discount.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'discount.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'price_list_dist.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'price_list_dist.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'price_list_dist.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'price_list_dist.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'price_list_dist_approved.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'price_list_dist_approved.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'price_list_dist_approved.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'price_list_dist_approved.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'discount_approve.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'discount_approve.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'discount_approve.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'discount_approve.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'shipping.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'shipping.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'shipping.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'shipping.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'office.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'office.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'office.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'office.delete', 'guard_name' => 'api']);


    }
}
