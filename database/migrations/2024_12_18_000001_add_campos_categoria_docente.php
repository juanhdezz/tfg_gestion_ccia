<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categoria', function (Blueprint $table) {
            // Verificar si las columnas ya existen antes de agregarlas
            if (!Schema::hasColumn('categoria', 'nombre_categoria')) {
                $table->string('nombre_categoria')->nullable()->after('id_categoria');
            }
            if (!Schema::hasColumn('categoria', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre_categoria');
            }
            if (!Schema::hasColumn('categoria', 'creditos_docentes')) {
                $table->integer('creditos_docentes')->nullable()->after('descripcion')->comment('Número de créditos que puede impartir esta categoría');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categoria', function (Blueprint $table) {
            if (Schema::hasColumn('categoria', 'creditos_docentes')) {
                $table->dropColumn('creditos_docentes');
            }
            if (Schema::hasColumn('categoria', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            if (Schema::hasColumn('categoria', 'nombre_categoria')) {
                $table->dropColumn('nombre_categoria');
            }
        });
    }
};
