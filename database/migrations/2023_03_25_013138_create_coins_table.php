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
            $table->string('id_name')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('symbol')->nullable();
            $table->bigInteger('current_price')->nullable();
            $table->bigInteger('market_cap')->nullable();
            $table->bigInteger('fully_diluted_valuation')->nullable();
            $table->string('market_cap_rank')->nullable();
            $table->bigInteger('total_volume')->nullable();
            $table->bigInteger('high_24h')->nullable();
            $table->bigInteger('low_24h')->nullable();
            $table->decimal('price_change_24h', 65, 30, true)->nullable();
            $table->decimal('price_change_percentage_24h', 65, 30, true)->nullable();
            $table->decimal('market_cap_change_24h', 65, 30, true)->nullable();
            $table->decimal('market_cap_change_percentage_24h', 65, 30, true)->nullable();
            $table->bigInteger('circulating_supply')->nullable();
            $table->bigInteger('total_supply')->nullable();
            $table->bigInteger('max_supply')->nullable();
            $table->bigInteger('ath')->nullable();
            $table->decimal('ath_change_percentage', 65, 30, true)->nullable();
            $table->string('ath_date')->nullable();
            $table->decimal('atl')->nullable();
            $table->decimal('atl_change_percentage', 65, 30, true)->nullable();
            $table->string('atl_date')->nullable();
            $table->string('last_updated')->nullable();
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
