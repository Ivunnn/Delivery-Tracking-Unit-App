<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unique('id_order', 'invoices_order_unique');
        });

        Schema::table('pengiriman', function (Blueprint $table) {
            $table->unique('id_order', 'pengiriman_order_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->dropUnique('pengiriman_order_unique');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_order_unique');
        });
    }
};
