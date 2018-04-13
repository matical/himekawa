<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use yuki\Foundation\Apk;

class ApkTest extends TestCase
{
    /**
     * @var string
     */
    protected $package = 'jp.co.bandainamcoent.BNEI0242';

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $url;

    public function setUp()
    {
        parent::setUp();

        Config::set('googleplay.apk_base_path', storage_path('apks'));
        Config::set('filesystems.disks.apks.url', 'http://localhost');

        $this->basePath = config('googleplay.apk_base_path');
        $this->url = config('filesystems.disks.apks.url');
    }

    /**
     * @return void
     */
    public function testPathResolvers()
    {
        $builder = new Apk(['apk_path' => $this->basePath]);

        $filename = $builder->resolveApkFilename($this->package, 130);
        $this->assertSame('jp.co.bandainamcoent.BNEI0242.130.apk', $filename);

        $baseDirectory = $builder->resolveApkDirectory();
        $this->assertSame($this->basePath, $baseDirectory);

        $apkDirectory = $builder->resolveApkDirectory($this->package);
        $this->assertSame($this->basePath . DIRECTORY_SEPARATOR . $this->package, $apkDirectory);

        $fullApkPathAndDirectory = $builder->resolveApkDirectory($this->package, 130);
        $this->assertSame($this->basePath . DIRECTORY_SEPARATOR . $this->package . DIRECTORY_SEPARATOR . 'jp.co.bandainamcoent.BNEI0242.130.apk', $fullApkPathAndDirectory);

        $expectedPath = "{$this->url}/{$this->package}/$filename";

        Storage::shouldReceive('url')
               ->with($this->package . '/' . $filename)
               ->andReturn($expectedPath);

        $path = $builder->resolveApkUrl($this->package, 130);
        $this->assertSame($expectedPath, $path);
    }
}
