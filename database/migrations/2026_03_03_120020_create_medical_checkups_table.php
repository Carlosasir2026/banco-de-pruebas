<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('medical_checkups')) return;

        Schema::create('medical_checkups', function (Blueprint $table) {
            $table->increments('id_checkup');

            $table->unsignedInteger('id_animal');
            $table->date('fecha_consulta');

            $table->string('tipo_consulta', 50)->nullable();
            $table->text('motivo');
            $table->string('estado_general', 50)->nullable();

            $table->text('tratamiento')->nullable();
            $table->text('medicacion')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index('id_animal');
            $table->index(['id_animal', 'fecha_consulta']);

            $table->foreign('id_animal')
                ->references('id_animal')->on('animales')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('medical_checkups')) return;
        Schema::dropIfExists('medical_checkups');
    }
};