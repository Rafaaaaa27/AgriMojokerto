<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel template siklus pertanian (padi, jagung, kedelai, dll)
        Schema::create('crop_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Padi, Jagung, Kedelai
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration_days'); // total durasi siklus (hari)
            $table->string('icon')->default('seedling');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel siklus pertanian aktif milik petani
        Schema::create('farming_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_template_id')->constrained()->onDelete('cascade');
            $table->string('name'); // nama siklus, contoh: "Padi Sawah Blok A"
            $table->date('start_date');
            $table->date('estimated_end_date');
            $table->date('actual_end_date')->nullable();
            $table->string('location')->nullable(); // lokasi lahan
            $table->decimal('area_hectares', 8, 2)->nullable(); // luas lahan (hektar)
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Tabel tahapan dalam siklus pertanian
        Schema::create('schedule_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farming_cycle_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Pengolahan Tanah, Penyemaian, Tanam, dll
            $table->integer('order'); // urutan tahapan
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days'); // durasi tahapan
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Tabel item kegiatan dalam tahapan
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_stage_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity'); // nama kegiatan
            $table->date('date');
            $table->string('time')->nullable(); // waktu pelaksanaan (contoh: "08:00")
            $table->string('status')->default('pending'); // pending, completed, skipped
            $table->text('notes')->nullable();
            $table->text('recommendations')->nullable(); // rekomendasi dari penyuluh
            $table->text('products')->nullable(); // produk/obat yang dibutuhkan (JSON)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_items');
        Schema::dropIfExists('schedule_stages');
        Schema::dropIfExists('farming_cycles');
        Schema::dropIfExists('crop_templates');
    }
};
