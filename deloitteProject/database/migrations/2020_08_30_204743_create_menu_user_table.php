<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menus_id');
            $table->unsignedBigInteger('users_id');
            $table->timestamps();

            $table->foreign('menus_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_user');
    }
}
