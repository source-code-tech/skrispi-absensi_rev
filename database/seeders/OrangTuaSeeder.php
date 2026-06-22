<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class OrangTuaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $namaUmum = ['Susanto', 'Yulianti', 'Budi', 'Siti', 'Agus', 'Dewi', 'Ahmad', 'Rina', 'Joko', 'Ani', 'Handoko', 'Putri'];

        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->error('❌ Data siswa kosong!');
            return;
        }

        foreach ($students as $student) {
            $namaDepan = $namaUmum[array_rand($namaUmum)];
            $namaOrangTua = $namaDepan . ' ' . $faker->lastName();
            
            // Menggunakan NISN sebagai pembeda unik agar tidak ada duplikasi email
            $nisn = $student->nisn ?? rand(100000, 999999);
            $emailOrangTua = strtolower($namaDepan) . $nisn . '@gmail.com';
            $password = strtolower($namaDepan) . '123';
            $phoneNumber = '08' . $faker->numerify('##########'); 

            try {
                // Gunakan updateOrCreate untuk menghindari error duplikasi email
                $user = User::updateOrCreate(
                    ['email' => $emailOrangTua],
                    [
                        'name'              => $namaOrangTua,
                        'password'          => Hash::make($password),
                        'role'              => 'orang_tua',
                        'is_approved'       => true,
                        'email_verified_at' => Carbon::now(),
                    ]
                );

                // Buat atau update profil Parent
                $parent = ParentModel::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name'            => $namaOrangTua,
                        'relation_status' => 'Ayah',
                        'phone_number'    => $phoneNumber, 
                    ]
                );

                // Gunakan updateOrInsert untuk relasi agar tidak error jika sudah ada
                DB::table('parent_student')->updateOrInsert(
                    ['parent_id' => $parent->id, 'student_id' => $student->id]
                );

            } catch (\Exception $e) {
                $this->command->error("Gagal untuk siswa {$student->name}: " . $e->getMessage());
            }
        }

        $this->command->info("✅ Seeder Berhasil! Data orang tua telah di-generate dengan format formal.");
    }
}