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
            $table->unsignedBigInteger('socios_id');
            $table->unsignedBigInteger('cuotas_id');

            $table->decimal('pagos_monto', 16, 2);
            $table->date('pagos_fecha');
            $table->timestamps();

            $table->foreign('socios_id')->references('id')->on('socios')->onDelete('cascade');
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
