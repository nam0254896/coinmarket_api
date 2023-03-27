<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_values', function (Blueprint $table) {
            $table->id();
            $table->string('coin_id');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('close');
            $table->float('volumefrom');
            $table->float('volumeto');
            $table->date('date');
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
        Schema::dropIfExists('coin_values');
    }
}
