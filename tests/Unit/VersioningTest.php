<?php

namespace Tests\Unit;

use Tests\TestCase;
use himekawa\WatchedApp;
use himekawa\AvailableApp;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VersioningTest extends TestCase
{
    use DatabaseMigrations;

    protected $watched;
    protected $available;

    /** @test */
    public function it_properly_determines_if_an_update_is_required()
    {
        $version = app('\yuki\Scrapers\Versioning');

        $this->createWatched();
        $this->assertTrue($version->areUpdatesAvailableFor('ss', 100));

        $this->createAvailable();
        $this->assertFalse($version->areUpdatesAvailableFor('ss', 99));
        $this->assertFalse($version->areUpdatesAvailableFor('ss', 100));
        $this->assertTrue($version->areUpdatesAvailableFor('ss', 101));
    }

    protected function createWatched()
    {
        $this->watched = WatchedApp::forceCreate([
            'name'           => 'starlight',
            'slug'           => 'ss',
            'original_title' => 'アイドルマスター シンデレラガールズ スターライトステージ',
            'package_name'   => 'ss',
        ]);
    }

    protected function createAvailable()
    {
        $this->available = AvailableApp::forceCreate([
            'app_id'       => $this->watched->id,
            'version_code' => 100,
            'version_name' => 'v1.0.0',
            'size'         => 100,
            'hash'         => 'ss',
        ]);
    }
}
