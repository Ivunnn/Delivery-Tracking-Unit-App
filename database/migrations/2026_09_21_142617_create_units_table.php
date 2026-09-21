<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('no_rangka', 50)->unique();
            $table->string('tipe_motor', 100);
            $table->string('warna', 50);
            $table->integer('tahun')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->enum('status', ['tersedia', 'dipesan', 'dikirim', 'terjual'])
                  ->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};