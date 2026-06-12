<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HariLiburController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $hariLiburs = HariLibur::orderBy('tanggal', 'desc')->get();
        return view('admin.hari_libur.index', compact('hariLiburs'));
    }

    public function store(Request $request)
    {
        if ($request->jenis_input === 'single') {
            $request->validate([
                'tanggal' => 'required|date|unique:hari_liburs,tanggal',
                'keterangan' => 'required|string|max:255'
            ]);

            HariLibur::create([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ]);

            return redirect()->route('admin.hari_libur.index')->with('success', 'Hari libur berhasil ditambahkan');
        } else {
            $request->validate([
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'keterangan' => 'required|string|max:255'
            ]);

            $startDate = Carbon::parse($request->tanggal_mulai);
            $endDate = Carbon::parse($request->tanggal_selesai);
            $createdCount = 0;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $exists = HariLibur::where('tanggal', $date->format('Y-m-d'))->exists();
                if (!$exists) {
                    HariLibur::create([
                        'tanggal' => $date->format('Y-m-d'),
                        'keterangan' => $request->keterangan
                    ]);
                    $createdCount++;
                }
            }

            if ($createdCount > 0) {
                return redirect()->route('admin.hari_libur.index')->with('success', "Berhasil menambahkan {$createdCount} hari libur!");
            } else {
                return redirect()->route('admin.hari_libur.index')->with('error', 'Semua tanggal di rentang sudah ada!');
            }
        }
    }

    public function update(Request $request, HariLibur $hariLibur)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_liburs,tanggal,'.$hariLibur->id,
            'keterangan' => 'required|string|max:255'
        ]);

        $hariLibur->update($request->all());

        return redirect()->route('admin.hari_libur.index')->with('success', 'Hari libur berhasil diperbarui');
    }

    public function destroy(HariLibur $hariLibur)
    {
        $hariLibur->delete();
        return redirect()->route('admin.hari_libur.index')->with('success', 'Hari libur berhasil dihapus');
    }
}

