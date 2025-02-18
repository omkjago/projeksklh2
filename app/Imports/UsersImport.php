<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToModel, WithHeadingRow
{
    public static $rowCount = 0;

    public function model(array $row)
    {
        // Validasi data dari setiap baris
        $validator = Validator::make($row, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'guru', 'siswa', 'orangtua'])],
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan null (data tidak akan diimpor)
            return null;
        }
        UsersImport::$rowCount++;

        // Jika validasi berhasil, buat instance pengguna baru
        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'role' => $row['role'],
        ]);
    }
    
}
