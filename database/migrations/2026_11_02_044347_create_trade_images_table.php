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
        Schema::create('trade_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trade_id')
                    ->constrained() //menghubungkan trades_id dengan primary di table trades
                    ->cascadeOnDelete(); // kalau data di trades dihapus, disini juga otomatis dihapus juga

            $table->string('image_path');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_image');
    }
};
