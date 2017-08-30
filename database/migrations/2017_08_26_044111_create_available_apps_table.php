<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailableAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')
                  ->unsigned();
            $table->integer('version_code');
            $table->string('version_name');
            $table->integer('size');
            $table->string('hash');
            $table->text('raw_badging')
                  ->nullable();

            $table->foreign('app_id')
                  ->references('id')
                  ->on('watched_apps');
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
        Schema::dropIfExists('available_apps');
    }
}
