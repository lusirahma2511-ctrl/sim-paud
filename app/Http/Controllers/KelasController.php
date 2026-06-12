<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Kelas::with('guru');

        if ($search) {
            $query->where('nama_kelas', 'like', '%' . $search . '%')
                  ->orWhereHas('guru', function($q) use ($search) {
                      $q->where('nama_guru', 'like', '%' . $search . '%');
                  });
        }

        $kelas = $query->get();
        $gurus = Guru::all();

        return view('kelas.index', compact('kelas', 'gurus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::all();
        return view('kelas.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'guru_id' => [
                'required',
                'exists:gurus,id',
                function ($attribute, $value, $fail) {
                    $exists = Kelas::where('guru_id', $value)->exists();
                    if ($exists) {
                        $fail('Guru ini sudah menjadi guru kelas di kelas lain!');
                    }
                }
            ],
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        $kelas->load(['guru', 'siswa']);
        return view('kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $gurus = Guru::all();
        return view('kelas.edit', compact('kelas', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required',
            'guru_id' => [
                'required',
                'exists:gurus,id',
                function ($attribute, $value, $fail) use ($id) {
                    $exists = Kelas::where('guru_id', $value)
                        ->where('id', '!=', $id)
                        ->exists();
                    if ($exists) {
                        $fail('Guru ini sudah menjadi guru kelas di kelas lain!');
                    }
                }
            ],
        ]);

        try {
            $kelas->update($request->all());
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '42S22') {
                return back()->with('error', 'Gagal update: Kolom guru_id belum ada di database. Silakan jalankan "php artisan migrate" di terminal Anda.');
            }
            throw $e;
        }

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $kelas = Kelas::findOrFail($id);
    $kelas->delete();

    return redirect()->route('admin.kelas.index')
                     ->with('success', 'Data berhasil dihapus');
}
}