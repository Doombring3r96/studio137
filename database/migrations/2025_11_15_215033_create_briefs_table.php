<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('briefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->unique()->constrained('services')->onDelete('cascade');
            $table->enum('tipo', ['logo','marca','cm']);
            $table->string('document_path', 500)->nullable();
            $table->dateTime('fecha_recibida');
            $table->json('contenido_json')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('briefs');
    }
};