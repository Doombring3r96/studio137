<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // SOLO agregar columnas que no existen
            if (!Schema::hasColumn('users', 'manager_id')) {
                $table->foreignId('manager_id')->nullable()->constrained('users')->after('role_id');
            }

            if (!Schema::hasColumn('users', 'razon_social')) {
                $table->string('razon_social', 255)->nullable()->after('manager_id');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('razon_social');
            }

            if (!Schema::hasColumn('users', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->after('updated_at');
            }

            if (!Schema::hasColumn('users', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->after('created_by');
            }

            // Índices
            $table->index(['role_id']);
            $table->index(['manager_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar columnas nuevas si existen
            if (Schema::hasColumn('users', 'manager_id')) {
                $table->dropForeign(['manager_id']);
                $table->dropColumn('manager_id');
            }
            if (Schema::hasColumn('users', 'razon_social')) {
                $table->dropColumn('razon_social');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('users', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('users', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
        });
    }
};
