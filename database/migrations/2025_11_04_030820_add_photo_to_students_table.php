<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // 🚨 Tambahkan kolom 'photo' setelah kolom 'class_id'
            $table->string('photo', 150)->nullable()->default('default_avatar.png')->after('class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // 🚨 Hapus kolom 'photo'
            $table->dropColumn('photo');
        });
    }
};