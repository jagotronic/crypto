<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('handler');
            $table->text('data');
            $table->text('icon_src')->nullable();
            $table->text('webpage_url')->nullable();
            $table->string('symbol')->unique();
            $table->decimal('usd_value', 16, 8)->nullable();
            $table->decimal('cad_value', 16, 8)->nullable();
            $table->decimal('btc_value', 16, 8)->nullable();
            $table->decimal('percent_change_1h', 16, 4)->nullable();
            $table->decimal('percent_change_24h', 16, 4)->nullable();
            $table->decimal('percent_change_7d', 16, 4)->nullable();
            $table->string('description')->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('currencies');
    }
}
