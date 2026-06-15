<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
     public function index(Request $request)
{
    $query = Siswa::with(['orangTua', 'kelas']);

    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('nama_siswa', 'like', '%' . $request->search . '%')
              ->orWhere('nisn', 'like', '%' . $request->search . '%');
        });
    }

    $siswa = $query->paginate(10)->withQueryString();

    $kelas = Kelas::all();

    // 🔥 INI YANG KURANG
    $orang_tuas = OrangTua::all();

    return view('siswa.index', compact('siswa', 'kelas', 'orang_tuas'));
}

    public function import(Request $request)
{
    // 1. Validasi file
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        // 2. Gunakan class SiswaImport yang sudah kamu buat
        Excel::import(new \App\Imports\SiswaImport, $request->file('file'));

        return back()->with('success', 'Data Siswa, Orang Tua, dan Akun User berhasil diimport!');
    } catch (\Exception $e) {
        // Jika ada error (misal NISN kembar atau kolom salah)
        return back()->with('error', 'Gagal import: ' . $e->getMessage());
    }
}

    public function create()
    {
        $orang_tuas = OrangTua::all();
        $kelas = Kelas::all();
        return response()->json(compact('orang_tuas', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required|string',
            'nama_panggilan' => 'nullable|string',
            'nik' => 'nullable|string',
            'nisn' => 'nullable|string',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ], []);

        // Check uniqueness for nik and nisn (excluding "-" values)
        if ($request->nik && $request->nik !== '-') {
            $exists = Siswa::where('nik', $request->nik)->exists();
            if ($exists) {
                return back()->withErrors(['nik' => 'NIK sudah terdaftar!'])->withInput();
            }
        }

        if ($request->nisn && $request->nisn !== '-') {
            $exists = Siswa::where('nisn', $request->nisn)->exists();
            if ($exists) {
                return back()->withErrors(['nisn' => 'NISN sudah terdaftar!'])->withInput();
            }
        }

        return DB::transaction(function() use ($request) {
            /**
             * 🔥 AUTO CREATE ORANG TUA (JIKA BELUM ADA)
             */
            $orangTuaId = $request->orang_tua_id;

            if (!$orangTuaId) {
                $orangTua = OrangTua::create([
                    'nama_ayah' => $request->nama_ayah ?? 'Tidak diketahui',
                    'nama_ibu'  => $request->nama_ibu ?? 'Tidak diketahui',
                    'user_id'   => null, // Nanti diupdate setelah user dibuat
                ]);

                $orangTuaId = $orangTua->id;
            }

            /**
             * 🔥 PROSES FOTO
             */
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $fotoPath = $file->storeAs('siswa', $filename, 'public');
            }

            /**
             * 🔥 GENERATE PASSWORD (BERDASARKAN TANGGAL LAHIR)
             */
            $password_raw = $request->tanggal_lahir 
                ? date('dmY', strtotime($request->tanggal_lahir)) 
                : '12345678';

            /**
             * 🔥 CREATE USER UNTUK ORANG TUA (HANYA JIKA ORANG TUA BARU ATAU BELUM PUNYA USER)
             */
            $orangTua = OrangTua::find($orangTuaId);
            
            // Use a default username if nisn is "-" or empty
            $username = ($request->nisn && $request->nisn !== '-') ? $request->nisn : 'user_' . time();
            
            if (!$orangTua->user_id) {
                $user = User::create([
                    'name' => $request->nama_siswa,
                    'username' => $username,
                    'password' => Hash::make($password_raw),
                    'role' => 'orang_tua',
                    'status' => 'Aktif',
                    'foto' => $fotoPath,
                ]);

                OrangTua::where('id', $orangTuaId)->update(['user_id' => $user->id]);
            } else {
                // Jika orang tua sudah punya user, buat user baru untuk NISN saudara ini
                User::create([
                    'name' => $request->nama_siswa,
                    'username' => $username,
                    'password' => Hash::make($password_raw),
                    'role' => 'orang_tua',
                    'status' => 'Aktif',
                    'foto' => $fotoPath,
                ]);
            }

            /**
             * 🔥 SIMPAN SISWA
             */
            // Set empty nik/nisn to "-"
            $nik = $request->nik ?: '-';
            $nisn = $request->nisn ?: '-';
            
            // Use siswa ID for barcode if nisn is "-"
            $barcode = $nisn !== '-' ? $nisn : 'SISWA_' . time();
            
            $siswa = Siswa::create([
                'orang_tua_id' => $orangTuaId,
                'kelas_id' => $request->kelas_id,
                'nama_siswa' => $request->nama_siswa,
                'nama_panggilan' => $request->nama_panggilan,
                'nik' => $nik,
                'nisn' => $nisn,
                'jk' => $request->jk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'anak_ke' => $request->anak_ke,
                'jumlah_saudara' => $request->jumlah_saudara,
                'alamat' => $request->alamat,
                'status' => $request->status ?? 'Aktif',
                'foto' => $fotoPath,
                'password' => Hash::make($password_raw),
                'barcode' => $barcode,
            ]);

            // Update barcode with siswa ID if it was using temp value
            if ($barcode !== $nisn) {
                $siswa->update(['barcode' => 'SISWA_' . $siswa->id]);
            }

            if ($request->ajax()) {
                return response()->json($siswa, 201);
            }

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa dan akun orang tua berhasil ditambahkan!');
        });
    }

    public function show(Siswa $siswa)
    {
        $siswa = Siswa::with(['orangTua', 'kelas'])->find($siswa->id);

        if (!$siswa) return abort(404);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $siswa = Siswa::with(['orangTua', 'kelas'])->find($siswa->id);
        return response()->json($siswa);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'orang_tua_id' => 'required|exists:orang_tuas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required|string',
            'nama_panggilan' => 'nullable|string',
            'nik' => 'nullable|string',
            'nisn' => 'nullable|string',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ], []);

        // Check uniqueness for nik and nisn (excluding "-" values and current siswa)
        if ($request->nik && $request->nik !== '-') {
            $exists = Siswa::where('nik', $request->nik)->where('id', '!=', $siswa->id)->exists();
            if ($exists) {
                return back()->withErrors(['nik' => 'NIK sudah terdaftar pada siswa lain!'])->withInput();
            }
        }

        if ($request->nisn && $request->nisn !== '-') {
            $exists = Siswa::where('nisn', $request->nisn)->where('id', '!=', $siswa->id)->exists();
            if ($exists) {
                return back()->withErrors(['nisn' => 'NISN sudah terdaftar pada siswa lain!'])->withInput();
            }
        }

        $data = $request->except('foto');

        // Set empty nik/nisn to "-"
        $data['nik'] = $request->nik ?: '-';
        $data['nisn'] = $request->nisn ?: '-';

        // Update password jika tanggal lahir berubah
        if ($request->tanggal_lahir) {
            $password_fix = date('dmY', strtotime($request->tanggal_lahir));
            $data['password'] = \Hash::make($password_fix);
        }

        // Update barcode: use nisn if it's not "-", otherwise use "SISWA_<id>"
        $data['barcode'] = $data['nisn'] !== '-' ? $data['nisn'] : 'SISWA_' . $siswa->id;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan bukan default
            if ($siswa->foto && $siswa->foto != 'default.png' && \Storage::disk('public')->exists($siswa->foto)) {
                \Storage::disk('public')->delete($siswa->foto);
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('siswa', $filename, 'public');
            $data['foto'] = $path;
        }

        $siswa->update($data);

        if ($request->ajax()) {
            return response()->json($siswa);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }
    public function kartu($id)
{
    $siswa = Siswa::with(['kelas', 'orangTua'])->findOrFail($id);
    return view('siswa.kartu', compact('siswa'));
}

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => 'Data siswa berhasil dihapus!']);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}