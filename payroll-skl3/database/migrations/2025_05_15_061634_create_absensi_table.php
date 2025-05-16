<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_absensi_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id(); // bigint, primary, auto_increment
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'tanpa keterangan']);
            $table->text('keterangan')->nullable();
            $table->timestamps(); // created_at, updated_at

            $table->unique(['karyawan_id', 'tanggal']); // Satu karyawan hanya bisa absen sekali sehari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};