<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->index(
                ['id_pengiriman', 'jam_update'],
                'trackings_pengiriman_jam_update_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->dropIndex('trackings_pengiriman_jam_update_index');
        });
    }
};
