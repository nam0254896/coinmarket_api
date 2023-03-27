<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinscmcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coinscmc', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('symbol')->nullable();
            $table->string('slug')->nullable();
            $table->bigInteger('num_market_pairs')->nullable();
            $table->string('date_added')->nullable();
            $table->json('tags')->nullable();
            $table->bigInteger('max_supply')->nullable();
            $table->bigInteger('circulating_supply')->nullable();
            $table->bigInteger('total_supply')->nullable();
            // $table->string('platform')->nullable();
            $table->integer('cmc_rank')->nullable();
            $table->bigInteger('self_reported_circulating_supply')->nullable();
            $table->bigInteger('self_reported_market_cap')->nullable();
            $table->decimal('tvl_ratio', 10, 10, true)->nullable();
            $table->string('last_updated')->nullable();
            $table->decimal('price', 65, 30, true)->nullable();
            $table->decimal('volume_24h', 65, 30, true)->nullable();
            $table->decimal('percent_change_1h', 65, 30, true)->nullable();
            $table->decimal('percent_change_24h', 65, 30, true)->nullable();
            $table->decimal('percent_change_7d', 65, 30, true)->nullable();
            $table->decimal('percent_change_30d', 65, 30, true)->nullable();
            $table->decimal('percent_change_60d', 65, 30, true)->nullable();
            $table->decimal('percent_change_90d', 65, 30, true)->nullable();
            $table->decimal('market_cap', 65, 30, true)->nullable();
            $table->decimal('fully_diluted_market_cap', 65, 30, true)->nullable();
            $table->decimal('tvl', 65, 30, true)->nullable();
            $table->string('last_updated_quote')->nullable();
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
        Schema::dropIfExists('coinscmc');
    }
}
