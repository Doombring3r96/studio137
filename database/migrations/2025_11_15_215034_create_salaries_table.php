<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pagador_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('empleado_id')->constrained('users')->onDelete('restrict');
            $table->decimal('cantidad', 12, 2);
            $table->date('fecha_pago');
            $table->string('comprobante_path', 500)->nullable();
            $table->enum('estado', ['pendiente','pagado','revisado'])->default('pendiente');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['empleado_id']);
            $table->index(['fecha_pago']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};