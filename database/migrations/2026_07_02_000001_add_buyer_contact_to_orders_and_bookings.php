<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('buyer_name')->nullable()->after('shipping_address');
            $table->string('buyer_phone')->nullable()->after('buyer_name');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('buyer_name')->nullable()->after('booking_date');
            $table->string('buyer_phone')->nullable()->after('buyer_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['buyer_name', 'buyer_phone']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['buyer_name', 'buyer_phone']);
        });
    }
};
