<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homeroom_teachers', function (Blueprint $table) {
            // Menambahkan kolom scan_token setelah user_id
            $table->string('scan_token', 80)->nullable()->unique()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('homeroom_teachers', function (Blueprint $table) {
            $table->dropColumn('scan_token');
        });
    }
};