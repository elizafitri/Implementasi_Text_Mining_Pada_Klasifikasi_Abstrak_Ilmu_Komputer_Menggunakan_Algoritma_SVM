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
            $table->increments('id');
            $table->unsignedInteger('id_term');
            $table->foreign('id_term')->references('id')->on('new_tokens')->onDelete('CASCADE');
            $table->decimal('tfidf', 5, 4);
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
