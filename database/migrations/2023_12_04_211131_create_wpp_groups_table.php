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
        Schema::create('wpp_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wpp_connect_id')->constrained('wpp_connects', 'id')->onDelete('cascade');
            $table->string('group_id');
            $table->string('name');
            $table->string('creation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wpp_groups');
    }
};
