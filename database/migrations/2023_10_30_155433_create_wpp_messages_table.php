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
        Schema::create('wpp_messages', function (Blueprint $table) {
            $table->id();
            $table->string("wppid");
            $table->foreignId('wpp_connect_id')->constrained('wpp_connects', 'id')->onDelete('cascade');
            $table->string('phone');
            $table->string('from');
            $table->string('to');
            $table->string('type');
            $table->longText('body')->nullable();
            $table->string('t');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wpp_messages');
    }
};
