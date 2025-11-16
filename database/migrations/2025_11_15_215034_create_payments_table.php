<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('servicio_id')->constrained('services')->onDelete('cascade');
            $table->decimal('cantidad', 12, 2);
            $table->dateTime('fecha_pago');
            $table->enum('tipo', ['mensual','unico']);
            $table->string('comprobante_path', 500)->nullable();
            $table->enum('estado', ['pendiente','pagado','revisado'])->default('pendiente');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['servicio_id']);
            $table->index(['cliente_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};