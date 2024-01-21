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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('password')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->foreignUuid('hotel_id')
                ->nullable()
                ->references('id')
                ->on('hotels')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('hotel_id');
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
        });
    }
};
