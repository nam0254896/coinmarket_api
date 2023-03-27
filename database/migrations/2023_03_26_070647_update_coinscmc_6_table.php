<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoinscmc6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coin_for_c_m_c_s', function (Blueprint $table) {
            $table->float('percent_change_1h')->nullable()->change();
            $table->float('percent_change_24h')->nullable()->change();
            $table->float('percent_change_7d')->nullable()->change();
            $table->float('percent_change_30d')->nullable()->change();
            $table->float('percent_change_60d')->nullable()->change();
            $table->float('percent_change_90d')->nullable()->change();
            $table->float('tvl_ratio')->nullable()->change();
            $table->float('market_cap')->nullable()->change();
            $table->float('fully_diluted_market_cap')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coin_for_c_m_c_s', function (Blueprint $table) {
            $table->bigInteger('percent_change_1h')->nullable()->change();
            $table->bigInteger('percent_change_24h')->nullable()->change();
            $table->bigInteger('percent_change_7d')->nullable()->change();
            $table->bigInteger('percent_change_30d')->nullable()->change();
            $table->bigInteger('percent_change_60d')->nullable()->change();
            $table->bigInteger('percent_change_90d')->nullable()->change();
            $table->bigInteger('tvl_ratio')->nullable()->change();
            $table->bigInteger('market_cap')->nullable()->change();
            $table->bigInteger('fully_diluted_market_cap')->nullable()->change();
        });
    }
}
