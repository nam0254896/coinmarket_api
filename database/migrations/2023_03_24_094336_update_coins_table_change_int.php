<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoinsTableChangeInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('symbol')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->float('current_price')->nullable()->change();
            $table->bigInteger('market_cap')->nullable()->change();
            $table->bigInteger('market_cap_rank')->nullable()->change();
            $table->bigInteger('total_volume')->nullable()->change();
            $table->float('high_24h')->nullable()->change();
            $table->float('low_24h')->nullable()->change();
            $table->float('price_change_24h')->nullable()->change();
            $table->float('price_change_percentage_24h')->nullable()->change();
            $table->bigInteger('market_cap_change_24h')->nullable()->change();
            $table->float('market_cap_change_percentage_24h')->nullable()->change();
            $table->bigInteger('circulating_supply')->nullable()->change();
            $table->bigInteger('total_supply')->nullable()->change();
            $table->bigInteger('max_supply')->nullable()->change();
            $table->string('ath')->nullable()->change();
            $table->string('ath_change_percentage')->nullable()->change();
            $table->string('ath_date')->nullable()->change();
            $table->string('atl')->nullable()->change();
            $table->string('atl_change_percentage')->nullable()->change();
            $table->string('atl_date')->nullable()->change();
            $table->string('last_updated')->nullable()->change();
            $table->string('id_name')->nullable()->change();
            $table->bigInteger('fully_diluted_valuation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
