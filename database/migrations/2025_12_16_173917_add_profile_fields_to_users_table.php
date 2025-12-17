<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('address');
            }
            // Kolom 'role' tidak perlu ditambah karena sudah ada
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['phone', 'address', 'photo', 'role']);
        });
    }
};