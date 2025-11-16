<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('autor_id')->constrained('users')->onDelete('restrict');
            
            $table->enum('tipo', ['propuestas','version1','version2'])->default('propuestas');
            $table->string('img_path', 500);
            $table->enum('estado', ['pendiente','enviado','rechazado','en_revision','corregido','entregado'])->default('pendiente');
            $table->enum('version', ['vertical','horizontal','una_tinta','negativo_una_tinta','negativo_color'])->nullable();
            $table->text('descripcion')->nullable();

            // ELIMINADO -> $table->foreignId('manual_id')->nullable()->constrained('manuals')->onDelete('set null');

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            $table->index(['servicio_id']);
            $table->index(['autor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logos');
    }
};
