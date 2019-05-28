<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf', function (Blueprint $table) {
            $table->increments('id');
            // $table->unsignedInteger('id_term');
            // $table->foreign('id_term')->references('id_token')->on('tokens')->onDelete('CASCADE');
            $table->unsignedInteger('id_doc');
            $table->foreign('id_doc')->references('id')->on('klasifikasis')->onDelete('CASCADE');
            $table->string('indeks', 150);
            $table->string('tf');
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
        Schema::dropIfExists('tf');
    }
}
