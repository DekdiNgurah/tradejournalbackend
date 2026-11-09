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
        if (!Schema::hasTable('trades')) {
            return;
        }

        Schema::table('trades', function (Blueprint $table) {
            if (!Schema::hasColumn('trades', 'pair')) {
                $table->string('pair', 20)->after('trade_date');
            }

            if (!Schema::hasColumn('trades', 'direction')) {
                $table->enum('direction', ['buy', 'sell'])->default('buy')->after('pair');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('trades')) {
            return;
        }

        Schema::table('trades', function (Blueprint $table) {
            if (Schema::hasColumn('trades', 'pair')) {
                $table->dropColumn('pair');
            }

            if (Schema::hasColumn('trades', 'direction')) {
                $table->dropColumn('direction');
            }
        });
    }
};
