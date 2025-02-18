<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Siswa; // Model Siswa
use App\Models\Kelas; // Model Kelas



class PresensiController extends Controller
{
    public function index()
{
    $user = Auth::user();
    if ($user->hasRole('admin')) {
        $presensi = Presensi::all(); // Ambil semua data presensi untuk admin
    } elseif ($user->hasRole('guru')) {
        // Ambil data presensi untuk siswa yang diajarkan oleh guru
        $presensi = Presensi::where('name', '!=', $user->name)->get(); // Ganti dengan logika yang sesuai
    } else {
        $presensi = []; // Jika bukan admin atau guru, tidak ada data
    }

    return view('presensi.riwayat', compact('presensi'));
}



    public function create()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $name = $user->name;
            $hariini = date('Y-m-d');
            $cek_presensi_masuk = DB::table('presensi')->where('name', $name)->where('tanggal_presensi', $hariini)->count();
            $cek_presensi_pulang = DB::table('presensi')->where('name', $name)->where('tanggal_presensi', $hariini)->whereNotNull('jam_out')->count();
            return view('presensi.create', compact('cek_presensi_masuk', 'cek_presensi_pulang'));
        } else {
            // Pengguna tidak terotentikasi, tangani di sini
            return redirect()->route('login')->with('error', 'Anda harus masuk untuk melakukan presensi.');
        }
    }
    public function guruadmin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $name = $user->name;
            $hariini = date('Y-m-d');
            $cek_presensi_masuk = DB::table('presensi')->where('name', $name)->where('tanggal_presensi', $hariini)->count();
            $cek_presensi_pulang = DB::table('presensi')->where('name', $name)->where('tanggal_presensi', $hariini)->whereNotNull('jam_out')->count();
            return view('presensi.presensi_adminguru', compact('cek_presensi_masuk', 'cek_presensi_pulang'));
        } else {
            // Pengguna tidak terotentikasi, tangani di sini
            return redirect()->route('login')->with('error', 'Anda harus masuk untuk melakukan presensi.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $name = $user->name;
            $tanggal_presensi = date('Y-m-d');
            $jam = date('H:i:s');

// Ambil relasi siswa dan kelas
$siswa = $user->siswa; // Relasi dengan model Siswa
$kelas = $siswa->kelas; // Relasi dengan model Kelas

$cek_presensi_masuk = DB::table('presensi')->where('siswa_id', $siswa->id)->where('tanggal_presensi', $tanggal_presensi)->count();
           
            if($cek_presensi_masuk > 0){
                $ket="out";
            }else{
                $ket="in";
            }

            $image = $request->image;
            $lokasi = $request->lokasi;
            $selectedLatitude = $request->selectedLatitude; // Ambil koordinat latitude lokasi kantor yang dipilih
            $selectedLongitude = $request->selectedLongitude; // Ambil koordinat longitude lokasi kantor yang dipilih

            $lokasiuser = explode(',', $lokasi);
            $latitudeuser = $lokasiuser[0];
            $longitudeuser = $lokasiuser[1];

            $jarak = $this->distance($selectedLatitude, $selectedLongitude, $latitudeuser, $longitudeuser);
            $radius = round($jarak['meters']);

            $folderPath = "public/uploads/absensi/";
            $formatName = $name . "-" . $tanggal_presensi . "-" . $ket;
            $image_parts = explode(";base64", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $formatName . ".png";
            $file = $folderPath . $fileName;

            if ($radius >= 50) {
                echo "error|Terlalu Jauh dari Kantor, jarak anda ". $radius . " meter dari kantor |";
            } else {

                if ($cek_presensi_masuk > 0) {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
// Ambil relasi siswa dan kelas
$siswa = $user->siswa; // Relasi dengan model Siswa
$kelas = $siswa->kelas; // Relasi dengan model Kelas

$cek_presensi_masuk = DB::table('presensi')->where('siswa_id', $siswa->id)->where('tanggal_presensi', $tanggal_presensi)->count();
                   if ($update) {
                        echo "success|Berhasil Presensi Pulang|out";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Gagal Presensi Pulang, Hubungi Admin|out";
                    }
                } else {
                    $data = [
                        'name' => $user->name,
                        'tanggal_presensi' => $tanggal_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'siswa_id' => $siswa->id, // Menyertakan siswa_id
                        'kelas_id' => $kelas->id, // Menyertakan kelas_id
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Berhasil Presensi Masuk|in";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Terjadi Error, Hubungi Admin|in";
                    }
                }
            }
        }
    }

    // Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }
}
