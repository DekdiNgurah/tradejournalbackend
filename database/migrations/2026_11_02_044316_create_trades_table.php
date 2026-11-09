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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    -> constrained() // menghubungkan user_id dengan primary di tabel users
                    ->cascadeOnDelete(); // kalau data user nya ilang, maka transaksinya juga ilang

            $table-> date('trade_date');
            $table-> string('pair', 20);    
            $table-> enum('direction', ['buy', 'sell']);

            $table-> decimal('entry_price', 15,5);
            $table-> decimal('exit_price', 15,5);
            $table-> decimal('lot_size', 10,3);

            $table->decimal('pnl', 15,2);

            $table->boolean('follow_plan');
            $table->text('notes')->nullable();

            $table->timestamps();

            // ini untuk membantu mencari data berdasarkan tanggal
            $table->index('trade_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
