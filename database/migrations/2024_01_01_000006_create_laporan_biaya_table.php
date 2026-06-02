<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_biaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->cascadeOnDelete();
            $table->enum('jenis_biaya', ['transport', 'makan', 'penginapan']);
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('file_bukti_path')->nullable();
            $table->string('file_bukti_name')->nullable();
            $table->string('file_bukti_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_biaya');
    }
};
