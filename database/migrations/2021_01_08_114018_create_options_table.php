<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->nullable();
            $table->integer('price')->nullable();
            $table->text('image')->nullable();
            $table->boolean('is_active')->default(false);
//            $table->timestamps();
        });
        Schema::create('item_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')
                ->references('id')
                ->on('options')
                ->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
        Schema::dropIfExists('item_options');
    }
}
