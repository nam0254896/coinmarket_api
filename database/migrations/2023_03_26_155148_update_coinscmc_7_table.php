<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoinscmc7Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->float('current_price')->nullable()->change();
            $table->float('high_24h')->nullable()->change();
            $table->float('low_24h')->nullable()->change();
            $table->float('ath')->nullable()->change();
            $table->float('ath_change_percentage')->nullable()->change();
            $table->float('price_change_24h')->nullable()->change();
            $table->float('price_change_percentage_24h')->nullable()->change();
            $table->float('market_cap_change_24h')->nullable()->change();
            $table->float('market_cap_change_percentage_24h')->nullable()->change();
            $table->float('atl')->nullable()->change();
            $table->float('atl_change_percentage')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->bigInteger('current_price')->nullable();
            $table->bigInteger('high_24h')->nullable();
            $table->bigInteger('low_24h')->nullable();
            $table->bigInteger('ath')->nullable();
            $table->decimal('ath_change_percentage', 65, 30, true)->nullable();
            $table->decimal('price_change_24h')->nullable();
            $table->decimal('price_change_percentage_24h')->nullable();
            $table->decimal('market_cap_change_24h')->nullable();
            $table->decimal('market_cap_change_percentage_24h')->nullable();
            $table->decimal('atl')->nullable();
            $table->decimal('atl_change_percentage', 65, 30, true)->nullable();
        });
    }
}
