<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("modelconfigs_id");
            $table->string("fillable_var");
            $table->string("type_input");
            $table->string("label");
            $table->text("desc")->default("");
            $table->boolean("show_in_form")->default(true);
            $table->boolean("show_in_table")->default(true);
            $table->boolean("show_in_edit")->default(true);
            $table->boolean("is_relationable")->default(false);
            $table->string("relationable_with_class")->default("")->nullable();
            $table->string("relationship_function")->default("")->nullable();
            $table->string("relationship_type_function")->default("")->nullable();
            $table->string("table_relation_many_to_many")->default("")->nullable();
            $table->string("rules")->default("")->nullable();
            $table->string("placeholder")->default("")->nullable();
            $table->string("class_field")->default("")->nullable();
            $table->string("value")->default("")->nullable();
            $table->string("attributes")->default("")->nullable();
            $table->integer("position_order")->nullable();
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
        Schema::dropIfExists('elements');
    }
}
