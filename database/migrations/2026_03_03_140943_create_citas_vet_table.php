<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('citas_vet')) return;

        Schema::create('citas_vet', function (Blueprint $table) {
            $table->increments('id_cita');

            $table->integer('id_animal');
            $table->date('fecha');
            $table->time('hora');

            $table->string('clinica_vet', 150);
            $table->string('telefono', 20)->nullable();
            $table->text('notas')->nullable();

            // Tu modelo timestamps=false, pero el controller rellena created_at
            $table->timestamp('created_at')->useCurrent();

            $table->index('id_animal');
            $table->index(['id_animal', 'fecha']);
            $table->index(['id_animal', 'fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas_vet');
    }
};