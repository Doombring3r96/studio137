<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('creador_id')->constrained('users')->onDelete('restrict');
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->enum('estado', ['pendiente','enviado','rechazado','en_revision','corregido','entregado'])->default('pendiente');
            $table->unsignedTinyInteger('correcciones_count')->default(0);
            $table->foreignId('ultimo_autor_correccion')->nullable()->constrained('users')->onDelete('set null');
            $table->string('document_path', 500)->nullable();
            $table->date('fecha_entrega_real')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['servicio_id']);
            $table->index(['creador_id']);
            $table->index(['estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_calendars');
    }
};