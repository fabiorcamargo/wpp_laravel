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
        Schema::create('wpp_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wpp_connect_id')->constrained('wpp_connects', 'id')->onDelete('cascade');
            $table->string('wpp_group_id')->constrained('wpp_groups', 'id')->onDelete('cascade');
            $table->string('name');
            $table->date('date');
            $table->string('time');
            $table->string('repeat');
            $table->string('period');
            $table->string('active');
            $table->longText('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wpp_schedules');
    }
};
