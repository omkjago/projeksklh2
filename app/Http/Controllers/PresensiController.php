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
    public function index(Request $request)
{
    $user = Auth::user();

    // Ambil daftar kelas untuk filter dropdown
    $kelasList = DB::table('kelas')->get();
    $selectedKelas = $request->input('kelas_id');
    $selectedTanggal = $request->input('tanggal_presensi');
    $selectedJamMasuk = $request->input('jam_in');
    $orderBy = $request->input('orderBy', 'presensi.name'); // Default kolom adalah nama
    $direction = $request->input('direction', 'asc'); // Default urutan naik (asc)

    // Query dasar untuk riwayat absensi
    $query = DB::table('presensi')
        ->leftJoin('siswa', 'presensi.siswa_id', '=', 'siswa.id') // Left join agar bisa menangani null siswa_id
        ->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
        ->select(
            'presensi.name',
            'presensi.tanggal_presensi',
            'presensi.jam_in',
            'presensi.jam_out',
            'presensi.foto_in',
            'presensi.foto_out',
            'kelas.nama_kelas',
            'presensi.siswa_id', // Tambahkan siswa_id untuk pengecekan null
            'presensi.status' 

        );

    // Jika user adalah guru, tampilkan hanya siswa sesuai kelas yang diampu
    if ($user->hasRole('guru') && !$user->hasRole('admin')) {
        $kelasYangDiampu = DB::table('kelas')
            ->where('guru_id', $user->id)
            ->pluck('id')
            ->toArray();

        $query->whereIn('siswa.kelas_id', $kelasYangDiampu);
    }

    // Filter berdasarkan kelas atau guru/admin
    if ($selectedKelas) {
        if ($selectedKelas == 'guru_admin') {
            // Menampilkan hanya guru/admin (siswa_id = null)
            $query->whereNull('siswa.id');
        } else {
            // Menampilkan siswa berdasarkan kelas yang dipilih
            $query->where('siswa.kelas_id', $selectedKelas);
        }
    } else {
        // Jika tidak ada filter, maka tampilkan data siswa (guru/admin tidak muncul)
        $query->whereNotNull('siswa.id');
    }
// Filter berdasarkan tanggal
if ($selectedTanggal) {
    $query->where('presensi.tanggal_presensi', $selectedTanggal);
}

// Filter berdasarkan jam masuk
if ($selectedJamMasuk) {
    $query->where('presensi.jam_in', $selectedJamMasuk);
}
    // Menambahkan urutan berdasarkan kolom dan arah urutan
    $riwayat = $query->orderBy($orderBy, $direction)->get();
    // Mengambil daftar kelas untuk dropdown filter
    $kelasList = Kelas::all();

    return view('presensi.riwayat', compact('riwayat', 'kelasList', 'selectedKelas', 'selectedTanggal', 'selectedJamMasuk', 'orderBy', 'direction'));
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

        // Ambil relasi siswa dan kelas hanya jika yang login adalah siswa
        if ($user->hasRole('siswa')) {
            $siswa = $user->siswa; // Relasi dengan model Siswa
            $kelas = $siswa->kelas; // Relasi dengan model Kelas
        } else {
            // Untuk admin dan guru, tidak perlu relasi siswa dan kelas
            $siswa = null; 
            $kelas = null;
        }

        $cek_presensi_masuk = DB::table('presensi')
                                ->where('siswa_id', $siswa ? $siswa->id : null)
                                ->where('tanggal_presensi', $tanggal_presensi)
                                ->count();
        
                                if ($cek_presensi_masuk > 0) {
                                    $status = 'Masuk';
                                } else {
                                    // Cek jika status Izin atau Sakit
                                    $status = $request->input('status', 'Alpha'); // Default ke 'Alpha'
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
        $formatName = $name . "-" . $tanggal_presensi . "-" . $status;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($radius >= 50) {
            echo "error|Terlalu Jauh dari Kantor, jarak anda " . $radius . " meter dari kantor |";
        } else {
            if ($cek_presensi_masuk > 0) {
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];

                $update = DB::table('presensi')
        ->where('siswa_id', $siswa ? $siswa->id : null)
        ->where('tanggal_presensi', $tanggal_presensi)
        ->update($data_pulang);
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
                    'siswa_id' => $siswa ? $siswa->id : null, 
                    'kelas_id' => $kelas ? $kelas->id : null, 
                    'status' => $status,
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
