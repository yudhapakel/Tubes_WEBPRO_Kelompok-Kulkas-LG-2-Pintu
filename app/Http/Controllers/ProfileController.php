<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user(); 
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $input = $request->only(['name', 'email', 'phone', 'address']);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                $oldPhotoPath = public_path('images/profile/' . $user->photo);
                if (File::exists($oldPhotoPath)) {
                    File::delete($oldPhotoPath);
                }
            }

            if (!File::exists(public_path('images/profile'))) {
                File::makeDirectory(public_path('images/profile'), 0755, true);
            }

            $fileName = time() . '_' . $user->id . '.' . $request->photo->extension();
            $request->file('photo')->move(public_path('images/profile'), $fileName);

            $input['photo'] = $fileName;
        }
        $user->update($input);
    
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
}