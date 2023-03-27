<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoinscmc4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coin_for_c_m_c_s', function (Blueprint $table) {
            $table->json('tags')->nullable()->change();
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
            $table->json('tags')->nullable()->change();
        });
    }
}
