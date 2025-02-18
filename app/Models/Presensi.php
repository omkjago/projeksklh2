<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'name',
        'tanggal_presensi',
        'jam_in',
        'jam_out',
        'foto_in',
        'foto_out',
        'lokasi_in',
        'lokasi_out',
        'status',
    ];

    protected $casts = [
        'tanggal_presensi' => 'date',
        'jam_in' => 'datetime',
        'jam_out' => 'datetime'
    ];

    // **Pastikan name diisi dan unik per hari**
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (empty($model->name)) {
                throw new \Exception("Nama tidak boleh kosong");
            }
            if (Presensi::where('name', $model->name)->where('tanggal_presensi', $model->tanggal_presensi)->exists()) {
                throw new \Exception("Anda sudah presensi hari ini!");
            }
        });
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
