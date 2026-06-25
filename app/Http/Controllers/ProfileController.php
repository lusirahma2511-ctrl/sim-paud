<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Email hanya wajib untuk role bukan orang tua
        if (!in_array($user->role, ['orang_tua'])) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        if ($request->filled('password')) {
            $rules['password'] = 'min:8|confirmed';
        }

        $request->validate($rules);

        // UPDATE DATA DASAR
        $user->name = $request->name;
        
        // Hanya update email jika role bukan orang tua dan email diisi
        if (!in_array($user->role, ['orang_tua'])) {
            $user->email = $request->email;
        }

        // UPLOAD FOTO
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile', 'public');
            $user->foto = $path;
        }

        // UPDATE PASSWORD (JIKA DIISI)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->is_default_password = false;
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}