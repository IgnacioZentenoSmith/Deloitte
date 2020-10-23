<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCumplimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cumplimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuotas_id');

            $table->decimal('cumplimientos_porcentaje', 3, 2);
            $table->date('cumplimientos_fecha');
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
        Schema::dropIfExists('cumplimientos');
    }
}
