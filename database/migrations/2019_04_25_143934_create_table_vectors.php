<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVectors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_vectors', function (Blueprint $table) {
            $table->increments('id_vector');
            $table->unsignedInteger('id_term');
            $table->foreign('id_term')->references('id_token')->on('tokens')->onDelete('CASCADE');
            $table->string('vector_doc');
            $table->string('total_vector');
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
        Schema::dropIfExists('table_vectors');
    }
}
