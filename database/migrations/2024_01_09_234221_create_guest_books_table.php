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
        Schema::create('guest_books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('client_first_name');
            $table->string('client_last_name');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('extra_comment')->nullable();
            $table->foreignUuid('hotel_id')
                ->nullable()
                ->references('id')
                ->on('hotels')
                ->onDelete('set null');
            $table->string('language', 3);
            $table->string('country', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_books');
    }
};
