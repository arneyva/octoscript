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
        Schema::create('postingan', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
        $table->string('post_title'); // Untuk judul post
        $table->string('brand'); // Untuk brand
        $table->string('platform'); // Untuk platform
        $table->date('due_date'); // Untuk due date (tipe data date)
        $table->decimal('payment', 10, 2); // Untuk payment (tipe data decimal)
        $table->integer('status'); // Untuk status (tipe data integer)
        $table->timestamps(); // Kolom created_at dan updated_at
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postingan');
    }
};
