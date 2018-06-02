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
            $table->unsignedInteger('available_app_id');
            $table->text('raw_badging');

            $table->foreign('available_app_id')
                  ->references('id')
                  ->on('available_apps');
        });

        AvailableApp::all()->each(function (AvailableApp $app) {
            $app->badging()->create(['raw_badging' => $app->raw_badging]);
        });

        Schema::table('available_apps', function (Blueprint $table) {
            $table->dropColumn('raw_badging');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('available_apps', function (Blueprint $table) {
            $table->text('raw_badging')
                  ->nullable();
        });

        Schema::dropIfExists('badgings');
    }
}
