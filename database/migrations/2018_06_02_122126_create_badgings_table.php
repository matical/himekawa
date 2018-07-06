<?php

use himekawa\AvailableApp;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBadgingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('badgings', function (Blueprint $table) {
            $table->unsignedInteger('available_app_id')
                  ->primary();
            $table->text('raw_badging');

            $table->foreign('available_app_id')
                  ->references('id')
                  ->on('available_apps')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('badgings');
    }
}
