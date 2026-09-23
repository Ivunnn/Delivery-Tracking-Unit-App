<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'kota'], 'users_role_kota_index');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->index('status', 'units_status_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'orders_status_created_at_index');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('status_bayar', 'invoices_status_bayar_index');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_status_bayar_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_at_index');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex('units_status_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_kota_index');
        });
    }
};
