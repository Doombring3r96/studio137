<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('entidad_tipo', 50)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->string('tipo', 50)->nullable();
            $table->text('mensaje');
            $table->boolean('is_read')->default(false);
            $table->boolean('delivery_push')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['entidad_tipo', 'entidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};