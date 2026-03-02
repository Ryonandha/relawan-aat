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
    Schema::create('event_registrations', function (Blueprint $table) {
        $table->id();
        // Relasi ke user (relawan) dan event
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        
        // Status untuk auto-sertifikat nantinya
        $table->boolean('is_attended')->default(false); 
        $table->string('certificate_path')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
