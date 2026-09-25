<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['id_customer', 'created_at'], 'orders_customer_created_at_index');
        });

        Schema::table('pengiriman', function (Blueprint $table) {
            $table->index(['id_driver', 'status', 'created_at'], 'pengiriman_driver_status_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->dropIndex('pengiriman_driver_status_created_at_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_customer_created_at_index');
        });
    }
};
