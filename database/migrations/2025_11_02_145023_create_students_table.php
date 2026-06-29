<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 10)->unique(); // Nomor Induk Siswa Nasional
            $table->string('nis', 20)->unique()->nullable(); // Nomor Induk Siswa (Opsional)
            $table->string('name', 100);
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->foreignId('class_id')->constrained('classes')->onDelete('restrict'); // Relasi ke tabel classes
            
            $table->string('barcode_data', 50)->unique(); // Data unik untuk Barcode
            $table->string('phone_number', 20)->nullable(); // Nomor HP siswa (opsional)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};