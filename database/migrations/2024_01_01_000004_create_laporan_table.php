<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_staff_id')->constrained('kegiatan_staff')->cascadeOnDelete();
            $table->text('kesimpulan')->nullable();
            $table->decimal('total_transport', 15, 2)->default(0);
            $table->decimal('total_makan', 15, 2)->default(0);
            $table->decimal('total_penginapan', 15, 2)->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
