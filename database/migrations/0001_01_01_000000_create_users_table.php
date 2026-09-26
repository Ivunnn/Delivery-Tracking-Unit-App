<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // The original migration may have stopped after creating the first
        // invoices table. Repair that partial state without deleting data.
        if (Schema::hasTable('users')) {
            if (Schema::hasTable('invoices')) {
                Schema::table('invoices', function (Blueprint $table) {
                    if (!Schema::hasColumn('invoices', 'bukti_bayar')) {
                        $table->string('bukti_bayar')->nullable();
                    }

                    if (!Schema::hasColumn('invoices', 'tgl_upload_bukti')) {
                        $table->timestamp('tgl_upload_bukti')->nullable();
                    }

                    if (!Schema::hasColumn('invoices', 'status_verifikasi')) {
                        $table->enum('status_verifikasi', [
                            'belum_upload',
                            'menunggu_verifikasi',
                            'diterima',
                            'ditolak',
                        ])->default('belum_upload');
                    }

                    if (!Schema::hasColumn('invoices', 'catatan_tolak')) {
                        $table->text('catatan_tolak')->nullable();
                    }
                });
            }

            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'driver', 'customer'])->default('customer');
            $table->boolean('is_active')->default(true);

            // Profil customer
            $table->string('nama_toko', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('no_ktp', 20)->unique()->nullable();
            $table->string('no_sim', 20)->nullable();
            $table->enum('status', ['tersedia', 'bertugas'])->default('tersedia');
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('no_rangka', 50)->unique();
            $table->string('tipe_motor', 100);
            $table->string('warna', 50);
            $table->integer('tahun')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('foto')->nullable(); // ← tambah ini
            $table->enum('status', ['tersedia', 'dipesan', 'dikirim', 'terjual'])
                ->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
        
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_customer')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_unit')->constrained('units')->cascadeOnDelete();
            $table->string('kode_order', 20)->unique();
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'selesai',
            ])->default('menunggu');
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')->constrained('orders')->cascadeOnDelete();
            $table->string('kode_invoice', 20)->unique();
            $table->decimal('harga', 15, 2);
            $table->decimal('biaya_pengiriman', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status_bayar', [
                'belum_bayar',
                'sudah_bayar',
            ])->default('belum_bayar');
            $table->timestamp('paid_at')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->timestamp('tgl_upload_bukti')->nullable();
            $table->enum('status_verifikasi', [
                'belum_upload',
                'menunggu_verifikasi',
                'diterima',
                'ditolak',
            ])->default('belum_upload');
            $table->text('catatan_tolak')->nullable();
            $table->timestamps();
        });

        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('id_driver')->constrained('drivers')->cascadeOnDelete();
            $table->string('kode_pengiriman', 20)->unique();
            $table->date('tanggal_kirim');
            $table->date('estimasi_tiba')->nullable();
            $table->string('tujuan', 255);
            $table->enum('status', [
                'menunggu',
                'berangkat',
                'dalam_perjalanan',
                'tiba',
                'selesai',
            ])->default('menunggu');
            $table->timestamps();
        });

        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengiriman')->constrained('pengiriman')->cascadeOnDelete();
            $table->string('status_tracking', 50);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('lokasi', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('jam_update')->useCurrent();
        });

        Schema::create('bukti_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengiriman')->constrained('pengiriman')->cascadeOnDelete();
            $table->string('foto_bukti');
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu_upload')->useCurrent();
        });

        Schema::create('rekening_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank', 50);
            $table->string('no_rekening', 30);
            $table->string('atas_nama', 100);
            $table->string('logo', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_pengiriman');
        Schema::dropIfExists('trackings');
        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('rekening_bank');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('units');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};