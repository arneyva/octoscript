<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('postingan', function (Blueprint $table) {
            // Ubah tipe data status menjadi ENUM
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postingan', function (Blueprint $table) {
            // Rollback ke integer jika migration dibatalkan
            $table->integer('status')->default(0)->change();
        });
    }
};
