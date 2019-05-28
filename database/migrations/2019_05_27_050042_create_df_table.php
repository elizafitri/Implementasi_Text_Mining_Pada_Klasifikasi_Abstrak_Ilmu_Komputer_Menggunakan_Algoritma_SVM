<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('df', function (Blueprint $table) {
            $table->increments('id');
            // $table->unsignedInteger('id_term');
            // $table->foreign('id_term')->references('id_token')->on('tokens')->onDelete('CASCADE');
            $table->string('df');
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
        Schema::dropIfExists('df');
    }
}
