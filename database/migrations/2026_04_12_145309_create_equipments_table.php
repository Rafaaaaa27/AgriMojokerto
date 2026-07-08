<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('traktor');
            $table->string('crop_type')->default('all');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('hari');
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
