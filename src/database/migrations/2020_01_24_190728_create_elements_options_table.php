<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elements_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("element_id");
            $table->unsignedBigInteger("option_id");
            $table->foreign("element_id")->references("id")->on("elements");
            $table->foreign("option_id")->references("id")->on("options");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elements_options');
    }
}
