<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logo_id')->unique()->constrained('logos')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('services')->onDelete('cascade');
            $table->string('manual_path', 500);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['servicio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manuals');
    }
};