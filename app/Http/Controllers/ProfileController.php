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
    
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                $oldPhotoPath = public_path('images/profile/' . $user->photo);
                if (File::exists($oldPhotoPath)) {
                    File::delete($oldPhotoPath);
                }
            }
            
            // Create directory if doesn't exist
            if (!File::exists(public_path('images/profile'))) {
                File::makeDirectory(public_path('images/profile'), 0755, true);
            }
            
            // Save new photo
            $fileName = time() . '_' . $user->id . '.' . $request->photo->extension();
            $request->file('photo')->move(public_path('images/profile'), $fileName);
            
            // Add photo to input array
            $input['photo'] = $fileName;
        }
    
        // Update user with all input data including photo if exists
        $user->update($input);
    
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
}