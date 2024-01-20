<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tourists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nationality');
            $table->string('passport_number')->nullable();
            $table->boolean('is_famous')->nullable();
            $table->string('email')->unique();
            $table->dateTime('arrival_date')->nullable();
            $table->dateTime('departure_date')->nullable();
            $table->string('code')->nullable();
            $table->boolean('is_valid')->nullable();
            $table->timestamps();
        });
        Schema::create('hotel_tourist', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->uuid('hotel_id');
            $table->uuid('tourist_id');
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('tourist_id')->references('id')->on('tourists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourists');
    }
};
