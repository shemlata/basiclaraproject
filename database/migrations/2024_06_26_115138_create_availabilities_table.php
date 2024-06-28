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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinitian_id')->nullable();
            $table->foreign('clinitian_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->dateTime('availability_start')->nullable();
            $table->dateTime('availability_end')->nullable();
            $table->string('availability_background_color')->default('#007bff');
            $table->string('availability_border_color')->default('#007bff');
            $table->enum('appointment_status', ['pending', 'confirmed', 'cancelled', 'completed'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
