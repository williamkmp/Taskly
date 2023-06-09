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
        Schema::create('card_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("card_id")->constrained();
            $table->foreignId("user_id")->constrained();
            $table->enum("type",["comment", "event"])->default("comment");
            $table->string("content")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_histories');
    }
};
