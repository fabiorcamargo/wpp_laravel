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
        Schema::table('wpp_batches', function (Blueprint $table) {
            $table->integer('delay')->after('status'); // Adiciona a coluna 'delay' após 'status'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wpp_batches', function (Blueprint $table) {
            $table->dropColumn('delay'); // Remove a coluna 'delay' caso a migração seja revertida
        });
    }
};
