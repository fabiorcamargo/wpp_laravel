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
        Schema::create('wpp_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wpp_connect_id')->constrained('wpp_connects', 'id')->onDelete('cascade');
            $table->longText('msg');
            $table->json('body');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wpp_batches');
    }
};
