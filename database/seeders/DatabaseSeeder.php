<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Jabatan;
use App\Models\Pangkat;
use App\Models\Status;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Status::create(['nama' => 'Aktif']);
        Status::create(['nama' => 'Nonaktif']);

        Pangkat::create(['golongan' => 'III', 'tmt' => now()]);
        Pangkat::create(['golongan' => 'IV', 'tmt' => now()]);
        Pangkat::create(['golongan' => 'V', 'tmt' => now()]);

        Jabatan::create(['nama' => 'Jabatan A', 'kelas' => 'I']);
        Jabatan::create(['nama' => 'Jabatan B', 'kelas' => 'II']);

        for($i = 0; $i < 10; $i++){
            User::create([
                'name' => 'Pegawai ' . $i,
                'nip' => '123' . $i,
                'email' => "pegawai$i@gmail.com",
                'password' => bcrypt("123"),
                'role' => 'user',
                'nik' => '123',
                'gender' => $i % 2 == 0 ? 'Laki-Laki' : "Perempuan",
                'tanggal_lahir' => now(),
                'pendidikan' => 'S-1',
                'gelar' => 'S.H',
                'alamat' => 'asd',
                'status_id' => ($i % 2) + 1,
                'pangkat_id' => ($i % 3) + 1,
                'jabatan_id' => ($i % 2) + 1,
            ]);
        }

        User::create([
            'name' => 'Admin',
            'nip' => '234',
            'email' => 'admin@gmail.com',
            'password' => bcrypt("123"),
            'role' => 'admin',
            'nik' => '123',
            'gender' => 'Laki-Laki',
            'tanggal_lahir' => now(),
            'pendidikan' => 'S-1',
            'gelar' => 'S.H',
            'alamat' => 'asd',
            'status_id' => 1,
            'pangkat_id' => 1,
            'jabatan_id' => 1,
        ]);

        User::create([
            'name' => 'Super Admin',
            'nip' => '345',
            'nik' => '456',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt("123"),
            'role' => 'superadmin',
            'gender' => 'Laki-Laki',
            'tanggal_lahir' => now(),
            'pendidikan' => 'S-1',
            'gelar' => 'S.Pd',
            'alamat' => 'asd',
            'status_id' => 1,
            'pangkat_id' => 2,
            'jabatan_id' => 2
        ]);

        foreach([1, 2, 3, 4, 5] as $userId){
            for($i = 1; $i < ($userId == 1 || $userId == 4 ? 40 : 30); $i++){
                $date = date('Y-m-d', strtotime(date('Y-m-d'). " - $i days"));
                if(!in_array(Carbon::parse(date('Y-m-d', strtotime($date)))->format('l'), ["Saturday", 'Sunday'])){
                    Absensi::create([
                        "tanggal" => $date,
                        'user_id' => $userId,
                        'status' => $i % 7 == 0 ? "Izin" : ($i % 10 == 0 ? "Dinas Luar" : "Hadir"),
                        'pagi' => $i % 7 == 0 ? null : ($i % 10 == 0 ? null : "06:30"),
                        'sore' => $i % 7 == 0 ? null : ($i % 10 == 0 ? null : "15:00"),
                    ]);
                }
            }
        }
    }
}