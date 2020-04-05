<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchedAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watched_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')
                  ->unique();
            $table->string('original_title');
            $table->string('package_name');
            $table->timestamp('disabled')
                  ->nullable();
            $table->boolean('use_split')
                  ->nullable();
            $table->boolean('use_additional')
                  ->nullable();

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
        Schema::dropIfExists('watched_apps');
    }
}
