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
            $table->string("wppid")->nullable();
            $table->foreignId('wpp_connect_id')->constrained('wpp_connects', 'id')->onDelete('cascade');
            $table->string('phone');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('type');
            $table->longText('body');
            $table->boolean('group');
            $table->string('t')->nullable();
            $table->string('status')->nullable();
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
