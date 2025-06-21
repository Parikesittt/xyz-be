<?php

namespace App\Http\Controllers\Api\Export;

use App\Http\Controllers\Controller;
use App\Exports\MasterItemExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemController extends Controller
{

    public function export(){

        $filename = 'inventory_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new MasterItemExport(), $filename);

    }
}
