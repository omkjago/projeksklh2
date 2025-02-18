<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfilePhotoController extends Controller
{
    /**
     * Menyimpan atau memperbarui foto profil ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:10240', // Mimes untuk jenis file, max untuk ukuran file (dalam kilobit)
        ]);

        // Dapatkan nama pengguna dari model pengguna
        $username = Auth::user()->name;
        // Susun nama file dengan menggunakan nama pengguna dan ekstensi asli
        $fileName = Str::slug($username) . '-profile.' . $request->file('photo')->getClientOriginalExtension();

        if ($request->hasFile('photo')) {
            $photoData = file_get_contents($request->file('photo')->getRealPath()); // Dapatkan data biner dari gambar yang diunggah
            // Simpan foto dengan nama yang sudah disusun
            Auth::user()->update(['profile_photo' => $photoData, 'profile_photo_path' => $fileName]); 
        }

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui.');
    }
}
