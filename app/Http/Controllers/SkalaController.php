<?php

namespace App\Http\Controllers;
use App\Models\SkalaNilai;
use Illuminate\Http\Request;

class SkalaController extends Controller
{
    public function index() {
        $skalas = SkalaNilai::orderBy('nilai', 'desc')->get();
        return view('admin.skala.index', compact('skalas'));
    }

    public function store(Request $request) {
        SkalaNilai::create($request->all());
        return back()->with('success', 'Skala berhasil ditambah');
    }

    public function update(Request $request, $id) {
        $skala = SkalaNilai::findOrFail($id);
        $skala->update($request->all());
        return back()->with('success', 'Skala berhasil diupdate');
    }

    public function destroy($id) {
        SkalaNilai::destroy($id);
        return back()->with('success', 'Skala berhasil dihapus');
    }
}
