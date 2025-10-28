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
        Schema::create('lote', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre');
            $t->timestamps();
        });

        Schema::create('receta_version', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre');
            $t->json('nutrientes')->nullable();
            $t->json('ingredientes')->nullable();
            $t->unsignedInteger('version')->default(1);
            $t->timestamps();
        });

        Schema::create('porcion', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre');
            $t->unsignedInteger('peso_gr');
            $t->timestamps();
        });

        Schema::create('estacion', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre');
            $t->unsignedInteger('capacidad')->nullable();
            $t->timestamps();
        });

        Schema::table('produccion_batch', function (Blueprint $t) {
            $t->decimal('rendimiento', 18, 2)->nullable()->after('merma_gr');
            $t->unsignedBigInteger('estacion_id')->nullable();
            $t->foreign('estacion_id')->references('id')->on('estacion');
            $t->unsignedBigInteger('receta_version_id')->nullable();
            $t->foreign('receta_version_id')->references('id')->on('receta_version');
            $t->unsignedBigInteger('porcion_id')->nullable();
            $t->foreign('porcion_id')->references('id')->on('porcion');

            $t->index(['receta_version_id', 'porcion_id'], 'idx_pb_rec_por');
            $t->index(['estacion_id'], 'idx_pb_est');
        });

        Schema::create('direccion', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre')->nullable();
            $t->string('linea1');
            $t->string('linea2')->nullable();
            $t->string('ciudad')->nullable();
            $t->string('provincia')->nullable();
            $t->string('pais')->nullable();
            $t->json('geo')->nullable();
            $t->timestamps();
        });

        Schema::create('ventana_entrega', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->dateTime('desde');
            $t->dateTime('hasta');
            $t->timestamps();
        });

        Schema::create('suscripcion', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre');
            $t->timestamps();
        });

        Schema::create('paciente', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('nombre');
            $t->string('documento')->nullable();
            $t->unsignedBigInteger('suscripcion_id')->nullable();
            $t->foreign('suscripcion_id')->references('id')->on('suscripcion')->onDelete('cascade');
            $t->timestamps();
        });

        Schema::create('etiqueta', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('orden_produccion_id')->nullable();
            $t->foreign('orden_produccion_id')->references('id')->on('orden_produccion')->onDelete('cascade');
            $t->unsignedBigInteger('lote_id')->nullable();
            $t->foreign('lote_id')->references('id')->on('produccion_batch')->onDelete('set null');
            $t->unsignedBigInteger('receta_version_id')->nullable();
            $t->foreign('receta_version_id')->references('id')->on('receta_version')->onDelete('set null');
            $t->unsignedBigInteger('suscripcion_id')->nullable();
            $t->foreign('suscripcion_id')->references('id')->on('suscripcion')->onDelete('set null');
            $t->unsignedBigInteger('paciente_id')->nullable();
            $t->foreign('paciente_id')->references('id')->on('paciente')->onDelete('set null');
            $t->json('qr_payload')->nullable();
            $t->timestamps();

            $t->index(['orden_produccion_id', 'lote_id']);
            $t->unique(['orden_produccion_id','lote_id','receta_version_id','paciente_id'], 'uk_etq_compuesta');
        });

        Schema::create('paquete', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('etiqueta_id')->nullable();
            $t->foreign('etiqueta_id')->references('id')->on('etiqueta')->onDelete('cascade');
            $t->unsignedBigInteger('ventana_id')->nullable();
            $t->foreign('ventana_id')->references('id')->on('ventana_entrega')->onDelete('cascade');
            $t->unsignedBigInteger('direccion_id')->nullable();
            $t->foreign('direccion_id')->references('id')->on('direccion')->onDelete('cascade');
            $t->timestamps();
        });

        Schema::table('item_despacho', function (Blueprint $t) {
            $t->unsignedBigInteger('paquete_id')->nullable();
            $t->foreign('paquete_id')->references('id')->on('paquete')->onDelete('cascade');
        });

        Schema::create('calendario', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->date('fecha');
            $t->string('sucursal_id');
            $t->timestamps();
            $t->unique(['fecha','sucursal_id']);
        });

        Schema::create('calendario_item', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('calendario_id')->nullable();
            $t->foreign('calendario_id')->references('id')->on('calendario')->onDelete('cascade');
            $t->unsignedBigInteger('item_despacho_id')->nullable();
            $t->foreign('item_despacho_id')->references('id')->on('item_despacho')->onDelete('cascade');
            $t->timestamps();

            $t->unique(['calendario_id','item_despacho_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario_item');
        Schema::dropIfExists('calendario');
        Schema::table('item_despacho', function (Blueprint $t) {
            $t->dropForeign('item_despacho_paquete_id_foreign');
            $t->dropColumn(['paquete_id']);
        });
        Schema::dropIfExists('paquete');
        Schema::dropIfExists('etiqueta');
        Schema::dropIfExists('paciente');
        Schema::dropIfExists('suscripcion');
        Schema::dropIfExists('ventana_entrega');
        Schema::dropIfExists('direccion');

        Schema::table('produccion_batch', function (Blueprint $t) {
            $t->dropForeign('produccion_batch_estacion_id_foreign');
            $t->dropForeign('produccion_batch_receta_version_id_foreign');
            $t->dropForeign('produccion_batch_porcion_id_foreign');
            $t->dropIndex('idx_pb_rec_por');
            $t->dropIndex('idx_pb_est');

            $t->dropColumn(['rendimiento', 'estacion_id', 'receta_version_id', 'porcion_id']);
        });

        Schema::dropIfExists('estacion');
        Schema::dropIfExists('porcion');
        Schema::dropIfExists('receta_version');
        Schema::dropIfExists('lote');
    }
};