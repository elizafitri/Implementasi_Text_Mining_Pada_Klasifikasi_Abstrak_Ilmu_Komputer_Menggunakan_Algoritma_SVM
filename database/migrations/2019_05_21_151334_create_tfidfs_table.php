<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTfidfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tfidfs', function (Blueprint $table) {
            $table->increments('id_bobot');
            $table->unsignedInteger('id_doc');
            $table->foreign('id_doc')->references('id')->on('klasifikasis')->onDelete('CASCADE');
            $table->string('vector_term', 2000);
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
        Schema::dropIfExists('tfidfs');
    }
}
