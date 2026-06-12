<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use Illuminate\Http\Request;

class OrangtuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orangTuas = Orangtua::all();
        return response()->json($orangTuas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orang_tua.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'pekerjaan_ayah' => 'required|string',
            'pekerjaan_ibu' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $data = $request->all();

        // Jika tidak ada user_id, gunakan ID user yang sedang login (admin)
        // Namun idealnya setiap orang tua punya user sendiri.
        if (!isset($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $orangTuas = Orangtua::create($data);

        return response()->json($orangTuas);
    }

    /**
     * Display the specified resource.
     */
    public function show(Orangtua $orang_tua)
    {
        return response()->json($orang_tua);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orangtua $orang_tua)
    {
        return response()->json($orang_tua);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orangtua $orang_tua)
    {
        $request->validate([
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'pekerjaan_ayah' => 'required|string',
            'pekerjaan_ibu' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
        ]);
        $data = $request->all();
        $orang_tua->update($data);
        return response()->json($orang_tua);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orangtua $orang_tua)
    {
        $orang_tua->delete();       
        return response()->json(null, 204);
    }
}
