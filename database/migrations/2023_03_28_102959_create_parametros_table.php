<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateParametrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('valor', 255)->nullable();
            $table->string('valor2',512)->nullable();
            $table->date('valor_date',)->nullable();
            $table->integer('valor_int',)->nullable();
            $table->double('valor_double',)->nullable();
            $table->string('valor_string1m',1024)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parametros');
    }
}
