<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\NilaiPerkembangan;

class PenilaianController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('kode')->get();
        $totalBobot = $kriterias->sum('bobot');

        return view('admin.penilaian.index', compact('kriterias', 'totalBobot'));
    }

    /**
     * ===============================
     * STORE
     * ===============================
     */
    public function store(Request $request)
    {
        // 🔥 SIMPAN NILAI
        if ($request->has('nilai')) {

            foreach ($request->nilai as $siswa_id => $nilaiKriteria) {
                foreach ($nilaiKriteria as $kriteria_id => $nilai) {

                    NilaiPerkembangan::updateOrCreate(
                        [
                            'siswa_id' => $siswa_id,
                            'kriteria_id' => $kriteria_id,
                        ],
                        [
                            'nilai' => $nilai,
                            'guru_id' => auth()->id()
                        ]
                    );
                }
            }

            return back()->with('success', 'Nilai berhasil disimpan');
        }

        // 🔥 LIMIT MAX 8
        if (Kriteria::count() >= 8) {
            return back()->with('error', '⚠️ Maksimal hanya 8 kriteria!');
        }

        // 🔥 VALIDASI
        $request->validate([
            'kode' => 'required|unique:kriterias,kode',
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'deskripsi' => 'nullable|string'
        ], [
            'kode.unique' => '⚠️ Kode sudah digunakan!',
        ]);

        Kriteria::create([
            'kode' => strtoupper($request->kode),
            'nama_kriteria' => $request->nama_kriteria,
            'bobot' => $request->bobot,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Kriteria berhasil ditambahkan');
    }

    /**
     * ===============================
     * UPDATE (FIX ERROR KAMU)
     * ===============================
     */
    public function update(Request $request, Kriteria $kriteria)
    {
        $request->validate([
            // 🔥 INI KUNCI NYA
            'kode' => 'required|unique:kriterias,kode,' . $kriteria->id,
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'deskripsi' => 'nullable|string',
        ], [
            'kode.unique' => '⚠️ Kode sudah dipakai!',
        ]);

        $kriteria->update([
            'kode' => strtoupper($request->kode),
            'nama_kriteria' => $request->nama_kriteria,
            'bobot' => $request->bobot,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Kriteria berhasil diperbarui');
    }

    /**
     * ===============================
     * DELETE
     * ===============================
     */
    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return back()->with('success', 'Kriteria berhasil dihapus');
    }

    /**
     * ===============================
     * SEED 8 KRITERIA (FIX)
     * ===============================
     */
    public function seed()
    {
        $kriterias = [
            ['nama_kriteria' => 'Keimanan & Ketakwaan', 'kode' => 'C1', 'bobot' => 0.15],
            ['nama_kriteria' => 'Kewargaan', 'kode' => 'C2', 'bobot' => 0.10],
            ['nama_kriteria' => 'Penalaran Kritis', 'kode' => 'C3', 'bobot' => 0.10],
            ['nama_kriteria' => 'Kreativitas', 'kode' => 'C4', 'bobot' => 0.10],
            ['nama_kriteria' => 'Kolaborasi', 'kode' => 'C5', 'bobot' => 0.15],
            ['nama_kriteria' => 'Kemandirian', 'kode' => 'C6', 'bobot' => 0.15],
            ['nama_kriteria' => 'Kesehatan', 'kode' => 'C7', 'bobot' => 0.15],
            ['nama_kriteria' => 'Komunikasi', 'kode' => 'C8', 'bobot' => 0.10],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::updateOrCreate(
                ['kode' => $kriteria['kode']],
                $kriteria
            );
        }

        return back()->with('success', '🔥 8 Kriteria berhasil dibuat!');
    }
}