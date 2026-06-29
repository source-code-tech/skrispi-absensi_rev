<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon; // Import Carbon untuk timestamp

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan Anda hanya membuat user jika tabel masih kosong
      //  if (User::where('role', 'super_admin')->exists()) {
       //     return;
       // }
        
        // 1. AKUN SUPER ADMIN (Otomatis Disetujui & Diverifikasi)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin Sekolah',
                'password' => Hash::make('password'), 
                'role' => 'super_admin',
                'is_approved' => true, 
                'email_verified_at' => Carbon::now(),
            ]
        );

        // 2. AKUN WALI KELAS (Otomatis Disetujui & Diverifikasi untuk Testing)
        User::updateOrCreate(
            ['email' => 'walikelas@sekolah.com'],
            [
                'name' => 'Wali Kelas X-A',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas',
                'is_approved' => true, 
                'email_verified_at' => Carbon::now(),
            ]
        );
        
        // 3. AKUN ORANG TUA (Otomatis Disetujui & Diverifikasi untuk Testing)
        User::updateOrCreate(
            ['email' => 'ortu@sekolah.com'],
            [
                'name' => 'Bapak Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'orang_tua',
                'is_approved' => true, 
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}