<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['identidad_corporativa','community_manager','marketing_digital']);
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->decimal('costo', 12, 2);
            $table->foreignId('cliente_user_id')->constrained('users')->onDelete('restrict');
            $table->enum('estado', ['activo','inactivo','culminado','cancelado'])->default('activo');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['cliente_user_id']);
            $table->index(['estado']);
            $table->index(['fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};