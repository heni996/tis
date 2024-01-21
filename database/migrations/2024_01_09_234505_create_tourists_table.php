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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourists');
    }
};
 Schema::create('hotel_tourist', function (Blueprint $table) {
            $table->foreignUuid('tourist_id')
                ->nullable()
                ->references('id')
                ->on('tourists')
                ->onDelete('cascade');
            $table->foreignUuid('hotel_id')
                ->nullable()
                ->references('id')
                ->on('hotels')
                ->onDelete('cascade');
            $table->timestamps(); // Add this line to include created_at and updated_at
            $table->unique(['hotel_id', 'tourist_id']);

        });
