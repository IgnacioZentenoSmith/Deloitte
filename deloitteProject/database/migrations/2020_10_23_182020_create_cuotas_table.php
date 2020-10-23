<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('socios_id');

            $table->decimal('cuotas_monto', 16, 2);
            $table->decimal('cuotas_valorPorRendir', 16, 2);
            $table->boolean('cuotas_retencion');
            $table->decimal('cuotas_retencionMonto', 16, 2);
            $table->date('cuotas_fecha');
            $table->date('cuotas_fechaCumplimiento');
            $table->timestamps();

            $table->foreign('socios_id')->references('id')->on('socios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuotas');
    }
}
