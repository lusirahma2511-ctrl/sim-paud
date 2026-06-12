<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Guru::query();
        
        if ($search) {
            $query->where('nama_guru', 'like', "%$search%")
                  ->orWhere('nip', 'like', "%$search%");
        }
        
        $gurus = $query->get();
        
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        // Normalisasi NIP: Jika diisi '-' atau kosong, set jadi null agar tidak kena validasi unique
        if ($request->nip == '-' || empty($request->nip)) {
            $request->merge(['nip' => null]);
        }

        $request->validate([
            'nip' => 'nullable|unique:gurus,nip',
            'nik' => 'nullable|unique:gurus,nik',
            'nama_guru' => 'required',
            'jk' => 'required',
            'ttl' => 'required|date',
            'alamat' => 'required',
            'jabatan' => 'required',
            'status' => 'nullable|in:Aktif,Nonaktif',
            'no_hp' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            'foto_guru' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'nip.unique' => 'NIP ini sudah terdaftar!',
            'nik.unique' => 'NIK ini sudah terdaftar!',
            'email.unique' => 'Email ini sudah terdaftar sebagai akun user!',
            'foto_guru.max' => 'Ukuran foto guru tidak boleh lebih dari 5MB.',
        ]);

        return DB::transaction(function() use ($request) {
            $data = $request->all();

            // Logika Barcode: Gunakan NIP jika ada, jika tidak gunakan random
            if ($request->nip && $request->nip != '-') {
                $data['barcode'] = $request->nip;
            } else {
                $data['barcode'] = 'G' . rand(100000000, 999999999);
            }

            // Handle foto upload
            if ($request->hasFile('foto_guru')) {
                $file = $request->file('foto_guru');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('guru', $filename, 'public');
                $data['foto_guru'] = $path;
            }

            // Password default dari tanggal lahir (ddmmyyyy)
            $password_raw = date('dmY', strtotime($request->ttl));

            // Tentukan role berdasarkan jabatan
            $role = 'guru';
            if (stripos($request->jabatan, 'Kepala Sekolah') !== false) {
                $role = 'kepala_sekolah';
            } elseif (stripos($request->jabatan, 'Admin') !== false) {
                $role = 'admin';
            } elseif (stripos($request->jabatan, 'Guru Kelas') !== false) {
                $role = 'guru_kelas';
            }

            /**
             * 🔥 CREATE USER OTOMATIS
             * Username = email (jika ada)
             */
            $user = User::create([
                'name' => $request->nama_guru,
                'username' => $request->email ?? 'guru_' . rand(1000, 9999),
                'email' => $request->email,
                'password' => Hash::make($password_raw),
                'status' => $request->status ?? 'Aktif',
                'role' => $role,
                'foto' => $data['foto_guru'] ?? null,
            ]);

            $data['user_id'] = $user->id;

            Guru::create($data);

            return redirect()->route('admin.guru.index')->with('success', 'Data Guru dan Akun User berhasil ditambahkan! 🔥');
        });
    }


    public function show(Guru $guru)
    {
        return view('admin.guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        // Normalisasi NIP: Jika diisi '-' atau kosong, set jadi null
        if ($request->nip == '-' || empty($request->nip)) {
            $request->merge(['nip' => null]);
        }

        $request->validate([
            'nip' => 'nullable|unique:gurus,nip,' . $guru->id,
            'nik' => 'nullable|unique:gurus,nik,' . $guru->id,
            'nama_guru' => 'required',
            'jk' => 'required',
            'ttl' => 'required|date',
            'alamat' => 'required',
            'jabatan' => 'required',
            'status' => 'nullable|in:Aktif,Nonaktif',
            'no_hp' => 'nullable|string',
            'email' => 'nullable|email',
            'foto_guru' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Dinaikkan ke 5MB
        ], [
            'nip.unique' => 'NIP ini sudah digunakan oleh guru lain!',
            'nik.unique' => 'NIK ini sudah digunakan oleh guru lain!',
            'foto_guru.max' => 'Ukuran foto guru tidak boleh lebih dari 5MB.',
        ]);

        $data = $request->all();

        // Logika Barcode: Jika NIP berubah dan tidak kosong, update barcode. 
        // Jika NIP kosong/- dan barcode belum ada, generate acak.
        if ($request->nip && $request->nip != '-') {
            $data['barcode'] = $request->nip;
        } elseif (!$guru->barcode || $guru->barcode == $guru->nip) {
            // Generate baru jika sebelumnya barcode ikut NIP tapi sekarang NIP kosong
            if (!$request->nip || $request->nip == '-') {
                $data['barcode'] = 'G' . rand(100000000, 999999999);
            }
        }

        // Handle foto upload
        if ($request->hasFile('foto_guru')) {
            // Hapus foto lama jika ada
            if ($guru->foto_guru && \Storage::disk('public')->exists($guru->foto_guru)) {
                \Storage::disk('public')->delete($guru->foto_guru);
            }
            
            $file = $request->file('foto_guru');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('guru', $filename, 'public');
            $data['foto_guru'] = $path;
        }

        $guru->update($data);

        // Update role user jika jabatan berubah
        if ($guru->user) {
            $role = 'guru';
            if (stripos($request->jabatan, 'Kepala Sekolah') !== false) {
                $role = 'kepala_sekolah';
            } elseif (stripos($request->jabatan, 'Admin') !== false) {
                $role = 'admin';
            } elseif (stripos($request->jabatan, 'Guru Kelas') !== false) {
                $role = 'guru_kelas';
            }
            
            $userData = ['role' => $role];
            if ($request->email) {
                $userData['username'] = $request->email;
                $userData['email'] = $request->email;
            }
            
            $guru->user->update($userData);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diperbarui! ✨');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Guru dihapus');
    }

}