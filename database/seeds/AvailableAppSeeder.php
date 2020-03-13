<?php

use Illuminate\Database\Seeder;

class AvailableAppSeeder extends Seeder
{
    public function run()
    {
        factory(himekawa\AvailableApp::class, 20)->create();
    }
}
