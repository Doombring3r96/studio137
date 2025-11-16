<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('services')->onDelete('cascade');
            $table->string('tarea_tipo', 100);
            $table->foreignId('assigned_to')->constrained('users')->onDelete('restrict');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['pendiente','en_proceso','completado','cancelado'])->default('pendiente');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['assigned_to']);
            $table->index(['servicio_id']);
            $table->index(['fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};