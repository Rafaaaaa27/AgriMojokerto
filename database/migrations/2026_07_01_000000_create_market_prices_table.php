<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('commodity');
            $table->integer('price');
            $table->date('date');
            $table->string('source')->nullable();
            $table->timestamps();

            $table->index(['commodity', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
