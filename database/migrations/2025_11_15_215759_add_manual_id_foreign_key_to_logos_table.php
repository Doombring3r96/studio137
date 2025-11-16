<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logos', function (Blueprint $table) {
            // Esta FK se agregará después de que exista la tabla manuals
            $table->foreignId('manual_id')->nullable()->constrained('manuals')->onDelete('set null')->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('logos', function (Blueprint $table) {
            $table->dropForeign(['manual_id']);
            $table->dropColumn('manual_id');
        });
    }
};