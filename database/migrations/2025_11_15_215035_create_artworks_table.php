<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained('publication_calendars')->onDelete('cascade');
            $table->foreignId('autor_id')->constrained('users')->onDelete('restrict');
            $table->date('fecha_pub')->nullable();
            $table->string('titulo', 255);
            $table->text('cuerpo')->nullable();
            $table->text('copy')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('img_path', 500)->nullable();
            $table->enum('tipo', ['color','venta'])->default('color');
            $table->enum('estado', ['pendiente','enviado','rechazado','aprobado'])->default('pendiente');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['calendar_id']);
            $table->index(['autor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};