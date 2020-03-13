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
            $table->id();
            $table->foreignId('app_id')
                  ->constrained()
                  ->on('watched_apps');
            $table->integer('version_code');
            $table->string('version_name');
            $table->integer('size');
            $table->string('hash');

            $table->timestamps();

            $table->index('created_at');
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
