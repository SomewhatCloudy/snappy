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
        Schema::create('postcodes', function (Blueprint $table) {
            $table->id();
            $table->string('postcode')->index();
            $table->string('place_name');
            $table->string('county')->nullable();
            $table->string('country')->nullable();
            $table->string('type')->nullable();
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->index(['latitude', 'longitude']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postcodes');
    }
};
