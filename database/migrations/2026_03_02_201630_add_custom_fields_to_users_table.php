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
        $table->string('sianas_id')->nullable()->unique()->after('email');
        $table->foreignId('secretariat_id')->nullable()->after('sianas_id')->constrained('secretariats')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['secretariat_id']);
        $table->dropColumn(['sianas_id', 'secretariat_id']);
    });
}
};
