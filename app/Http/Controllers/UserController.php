<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->search, function($q) use ($request){
            $q->where('name','like','%'.$request->search.'%')
              ->orWhere('email','like','%'.$request->search.'%')
              ->orWhere('username','like','%'.$request->search.'%');
        })->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    // ===================== STORE =====================
    public function store(Request $request)
    {
        // ================= ORANG TUA =================
        if ($request->role == 'orang_tua') {

            $request->validate([
                'name' => 'required|string|max:255',
                'nisn' => 'required|exists:siswas,nisn|unique:users,username',
                'role' => 'required|in:orang_tua',
                'status' => 'required|in:Aktif,Nonaktif',
            ]);

            $siswa = Siswa::where('nisn', $request->nisn)->first();

            $password = $siswa && $siswa->tanggal_lahir
                ? date('dmY', strtotime($siswa->tanggal_lahir))
                : '12345678';

            User::create([
                'name' => $request->name,
                'username' => $request->nisn,
                'email' => null,
                'password' => Hash::make($password),
                'role' => 'orang_tua',
                'status' => $request->status,
                'is_default_password' => true, // 🔥 WAJIB
            ]);

        } 
        // ================= ADMIN / GURU / KEPALA =================
        else {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:admin,guru,guru_kelas,kepala_sekolah',
                'status' => 'required|in:Aktif,Nonaktif',
            ]);

            User::create([
                'name' => $request->name,
                'username' => null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'is_default_password' => true, // 🔥 WAJIB
            ]);
        }

        return back()->with('success','User berhasil ditambahkan');
    }

    // ===================== RESET PASSWORD =====================
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = '12345678';

        if ($user->role == 'orang_tua') {
            $siswa = Siswa::where('nisn', $user->username)->first();
            if ($siswa && $siswa->tanggal_lahir) {
                $newPassword = date('dmY', strtotime($siswa->tanggal_lahir));
            }
        } else if (in_array($user->role, ['guru', 'kepala_sekolah', 'admin'])) {
            $guru = \App\Models\Guru::where('user_id', $user->id)->first();
            if ($guru && $guru->ttl) {
                $newPassword = date('dmY', strtotime($guru->ttl));
            }
        }

        $user->password = Hash::make($newPassword);
        $user->is_default_password = true;
        $user->save();

        return back()->with([
            'success' => 'Password berhasil direset.',
            'new_password' => $newPassword
        ]);
    }

    // ===================== UPDATE =====================
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,guru,guru_kelas,kepala_sekolah,orang_tua',
            'status' => 'required|in:Aktif,Nonaktif',
        ];

        if ($request->role != 'orang_tua') {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
        }

        if ($request->role == 'orang_tua') {
            $rules['username'] = 'required|unique:users,username,' . $user->id;
        }

        if ($request->password) {
            $rules['password'] = 'min:6|confirmed';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'username' => $request->role == 'orang_tua' ? $request->username : null,
            'email' => $request->role != 'orang_tua' ? $request->email : null,
            'role' => $request->role,
            'status' => $request->status,
        ];

        // 🔐 PASSWORD UPDATE
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
            $data['is_default_password'] = false; // 🔥 WAJIB
        }

        $original = $user->getOriginal();

            // cek apakah ada perubahan
            $isChanged = false;
            foreach ($data as $key => $value) {
                if ($original[$key] != $value) {
                    $isChanged = true;
                    break;
                }
            }

            // cek password juga
            if ($request->password) {
                $isChanged = true;
            }

            if (!$isChanged) {
                return back()->with('info', 'Tidak ada perubahan data');
            }

            $user->update($data);

            return back()->with('success','User berhasil diupdate');
    }

    // ===================== SHOW / DETAIL =====================
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // ===================== DELETE =====================
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
