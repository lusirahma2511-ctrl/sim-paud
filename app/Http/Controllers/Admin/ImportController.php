<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        try {
            // Panggil file SiswaImport untuk memproses isinya
            Excel::import(new SiswaImport, $request->file('file'));
            return back()->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
