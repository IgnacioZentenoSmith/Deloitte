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
            $table->unsignedBigInteger('socios_id');

            $table->decimal('cumplimientos_porcentaje', 5, 2);
            $table->string('cumplimientos_fecha', 7);
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
        Schema::dropIfExists('cumplimientos');
    }
}
