<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Imports\Import;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // Import
    public function import(Request $request)
    {
        $model = $request->model;
        Excel::import(new Import($model), request()->file);
        return response()->json(['success' => true, 'message' => 'Import successfully.']);
    }
}
