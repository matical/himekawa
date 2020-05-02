<?php

namespace Tests\Unit;

use Tests\TestCase;
use yuki\Scrapers\Metainfo;
use Illuminate\Support\Facades\Cache;
use yuki\Repositories\MetainfoRepository;

class MetainfoRepositoryTest extends TestCase
{
    public $package = 'jp.test';

    /** @test */
    public function it_hits_the_cache()
    {
        $metainfo = $this->prophesize(Metainfo::class);
        $metainfo->fetch($this->package)
                 ->shouldBeCalled()
                 ->willReturn('testmetadata');

        $repo = new MetainfoRepository($metainfo->reveal());

        Cache::shouldReceive('remember')
             ->once()
             ->withSomeOfArgs("apk-metainfo:{$this->package}")
             ->andReturn($repo->getPackage($this->package));

        $this->assertSame('testmetadata', $repo->getPackageInfo($this->package));
    }
}
