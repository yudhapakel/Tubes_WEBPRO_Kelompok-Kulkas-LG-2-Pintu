<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user(); // Gunakan facade Auth agar lebih stabil
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Ambil semua input kecuali photo dulu
        $input = $request->only(['name', 'email', 'phone', 'address']);
    
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            
            // Simpan foto baru
            $fileName = time() . '_' . $user->id . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs('profile_photos', $fileName, 'public');
            
            // Masukkan path ke array input
            $input['photo'] = $path;
        }
    
        // Update user dengan data yang sudah digabung
        $user->update($input);
    
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
}