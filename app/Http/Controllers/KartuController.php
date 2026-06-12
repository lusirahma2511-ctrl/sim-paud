<?php
namespace App\Http\Controllers;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KartuController extends Controller
{
    public function kartu($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.kartu', compact('siswa'));
    }
}
