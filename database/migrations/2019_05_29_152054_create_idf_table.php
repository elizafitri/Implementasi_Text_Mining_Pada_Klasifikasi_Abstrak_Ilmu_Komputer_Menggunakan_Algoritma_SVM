<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idf', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_term');
            $table->foreign('id_term')->references('id')->on('new_tokens')->onDelete('CASCADE');
            $table->decimal('idf', 3, 4);
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
        Schema::dropIfExists('idf');
    }
}
