<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            // User ID adalah akun login untuk orang tua
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->string('name', 100); // Nama lengkap orang tua (bisa berbeda dari nama user)
            $table->string('relation_status', 10)->nullable(); // Misal: Ayah/Ibu/Wali
            $table->string('phone_number', 20)->unique(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};