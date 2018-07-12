<?php

use himekawa\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'           => 'sck',
            'email'          => 'yuki@yuki.yuki',
            'password'       => '$2y$10$Ia5E/Pj32peHAs0SbNKN9e0fGhFRm5LDmv6zh9FGSiVBelKfTGAqu', // changeme
            'telegram_token' => env('TELEGRAM_BOT_ROUTE', null),
        ]);

        $this->call(WatchedAppSeeder::class);
    }
}
