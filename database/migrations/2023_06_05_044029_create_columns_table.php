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
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("board_id")->constrained()->onDelete("cascade");
            $table->unsignedBigInteger("previous_id")->nullable();
            $table->unsignedBigInteger("next_id")->nullable();

            $table->foreign('previous_id')->references('id')->on('columns')->onDelete("set null");
            $table->foreign('next_id')->references('id')->on('columns')->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
