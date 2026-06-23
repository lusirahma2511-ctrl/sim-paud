<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use Illuminate\Http\Request;

class OrangTuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orangTuas = OrangTua::all();
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

        $orangTuas = OrangTua::create($data);

        return response()->json($orangTuas);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orangTua = OrangTua::findOrFail($id);
        return response()->json($orangTua);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $orangTua = OrangTua::findOrFail($id);
            return response()->json($orangTua);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengambil data orang tua: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'pekerjaan_ayah' => 'required|string',
            'pekerjaan_ibu' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
        ]);
        
        try {
            $orangTua = OrangTua::findOrFail($id);
            $data = $request->all();
            $orangTua->update($data);
            return response()->json($orangTua);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengupdate data orang tua: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $orangTua = OrangTua::findOrFail($id);
        $orangTua->delete();       
        return response()->json(null, 204);
    }
}
