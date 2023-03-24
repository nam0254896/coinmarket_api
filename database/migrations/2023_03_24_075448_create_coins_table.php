<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol');
            $table->string('image');
            $table->float('current_price');
            $table->integer('market_cap');
            $table->integer('market_cap_rank');
            $table->integer('total_volume');
            $table->float('high_24h');
            $table->float('low_24h');
            $table->float('price_change_24h');
            $table->float('price_change_percentage_24h');
            $table->float('market_cap_change_24h');
            $table->float('market_cap_change_percentage_24h');
            $table->float('circulating_supply');
            $table->float('total_supply');
            $table->float('max_supply');
            $table->string('ath');
            $table->string('ath_change_percentage');
            $table->string('ath_date');
            $table->string('atl');
            $table->string('atl_change_percentage');
            $table->string('atl_date');
            $table->string('last_updated');
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
        Schema::dropIfExists('coins');
       
    }
}
