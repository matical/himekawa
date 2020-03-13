<?php

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
            $table->foreignId('available_app_id')
                  ->constrained()
                  ->primary()
                  ->on('available_apps')
                  ->onDelete('cascade');

            $table->text('raw_badging');
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
