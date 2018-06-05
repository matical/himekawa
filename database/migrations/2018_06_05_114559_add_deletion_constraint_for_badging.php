<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletionConstraintForBadging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('badgings', function (Blueprint $table) {
            $table->dropForeign('badgings_available_app_id_foreign');

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
        Schema::table('badgings', function (Blueprint $table) {
            $table->dropForeign('badgings_available_app_id_foreign');

            $table->foreign('available_app_id')
                  ->references('id')
                  ->on('available_apps');
        });
    }
}
