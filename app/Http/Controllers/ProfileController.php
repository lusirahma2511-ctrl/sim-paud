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
    'email' => 'required|email|unique:users,email,' . $user->id,
    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
];

if ($request->filled('password')) {
    $rules['password'] = 'min:8|confirmed';
}

$request->validate($rules);

        // UPDATE DATA DASAR
        $user->name = $request->name;
        $user->email = $request->email;

        // UPLOAD FOTO
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile', 'public');
            $user->foto = $path;
        }

        // UPDATE PASSWORD (JIKA DIISI)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}