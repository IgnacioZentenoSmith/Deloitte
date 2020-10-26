<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuotas_id');

            $table->string('pagos_fecha', 7);
            $table->decimal('pagos_montoPagar', 16, 2);
            $table->boolean('pagos_retencion');
            $table->decimal('pagos_montoRetencion', 16, 2);
            $table->timestamps();

            $table->foreign('cuotas_id')->references('id')->on('cuotas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
