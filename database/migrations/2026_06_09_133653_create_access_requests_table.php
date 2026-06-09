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
        Schema::create('access_requests', function (Blueprint $table) {
            $table->id();
            // 💡 TAMBAHAN: Hubungkan ke tabel users (jika user dihapus, request ikut terhapus)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 💡 TAMBAHAN: Hubungkan ke tabel videos (jika video dihapus, request ikut terhapus)
            $table->foreignId('video_id')->constrained()->onDelete('cascade');

            // 💡 TAMBAHAN: Status izin (pending, approved, atau rejected)
            $table->string('status')->default('pending');

            // 💡 TAMBAHAN: Batas waktu akhir customer boleh menonton (diisi oleh admin)
            $table->dateTime('valid_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_requests');
    }
};
