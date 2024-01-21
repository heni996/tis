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
        Schema::create('hotel_tourist', function (Blueprint $table) {
            $table->foreignUuid('tourist_id')
                ->references('id')
                ->on('tourists')
                ->onDelete('cascade');
            $table->foreignUuid('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onDelete('cascade');
            $table->timestamps(); // Add this line to include created_at and updated_at
            $table->primary(['hotel_id', 'tourist_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_tourist');
    }
};
