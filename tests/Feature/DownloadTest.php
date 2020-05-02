<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use yuki\Scrapers\Download;
use yuki\Process\Supervisor;
use yuki\Scrapers\Store\StoreApp;
use yuki\Exceptions\PackageException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Prophecy\Prophecy\ProphecyInterface;
use yuki\Repositories\AvailableAppsRepository;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class DownloadTest extends TestCase
{
    public $package = 'jp.test';

    public $versionCode = 100;

    public $testPath = 'test/jp.test';

    /** @test */
    public function it_detects_already_downloaded_apps()
    {
        $storeApp = $this->prophesize(StoreApp::class);
        $storeApp->exists()->shouldBeCalled()->willReturn(true);
        $storeApp->verifyHash()->shouldBeCalled()->willReturn(true);

        $storeApp->relativePath()->shouldBeCalled()->willReturn($this->testPath);
        $storeApp->getPackageName()->shouldBeCalled()->willReturn($this->package);
        $storeApp->getVersionCode()->shouldBeCalled()->willReturn($this->versionCode);
        $this->expectException(PackageException::class);

        $this->checkPredictionsWithApp($storeApp);
    }

    /** @test */
    public function it_detects_faulty_downloads()
    {
        $storeApp = $this->prophesize(StoreApp::class);
        $storeApp->exists()->shouldBeCalled()->willReturn(true);
        $storeApp->verifyHash()->shouldBeCalled()->willReturn(false);

        $storeApp->deleteDownload()->shouldBeCalled()->willReturn(true);
        $storeApp->getPackageName()->shouldBeCalled()->willReturn($this->package);

        Storage::shouldReceive('disk->exists')->once()->with($this->package)->andReturnTrue();

        $this->checkPredictionsWithApp($storeApp);
    }

    /** @test */
    public function it_creates_missing_directories()
    {
        $storeApp = $this->prophesize(StoreApp::class);
        $storeApp->exists()->shouldBeCalled()->willReturn(false);
        $storeApp->getPackageName()->shouldBeCalled()->willReturn($this->package);

        Storage::shouldReceive('disk->exists')->once()->with($this->package)->andReturnFalse();
        Storage::shouldReceive('disk->makeDirectory')->once()->with($this->package);

        $this->checkPredictionsWithApp($storeApp);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_handles_timeouts()
    {
        $download = $this->downloadMock();
        $process = $this->createMock(Process::class);

        $supervisor = Mockery::mock('alias:' . Supervisor::class);
        $supervisor->expects('command')->andReturnSelf();
        $supervisor->shouldReceive('setTimeout', 'setOutputAvailability');
        $supervisor->expects('execute')->andThrow(ProcessTimedOutException::class, $process, 1);

        $storeApp = $this->prophesize(StoreApp::class);
        $storeApp->exists()->shouldBeCalled()->willReturn(false);
        $storeApp->getPackageName()->shouldBeCalled()->willReturn($this->package);
        $storeApp->fullPath()->shouldBeCalled()->willReturn($this->testPath);
        $storeApp->deleteDownload()->shouldBeCalled();
        $this->expectException(ProcessTimedOutException::class);

        $download->withApp($storeApp->reveal())->fetch();
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_fetches_apps()
    {
        $download = $this->createPartialMock(Download::class, ['store']);

        $supervisor = Mockery::mock('alias:' . Supervisor::class);
        $supervisor->expects('command')->andReturnSelf();
        $supervisor->shouldReceive('setTimeout', 'setOutputAvailability');
        $supervisor->expects('execute');

        $storeApp = $this->prophesize(StoreApp::class);
        $storeApp->exists()->shouldBeCalled()->willReturn(false);
        $storeApp->getPackageName()->shouldBeCalled()->willReturn($this->package);
        $storeApp->fullPath()->shouldBeCalled()->willReturn($this->testPath);
        $storeApp->verifyHash()->shouldBeCalled()->willReturn(true);
        $storeApp->expectedHash()->shouldBeCalled()->willReturn('hash');

        $download->withApp($storeApp->reveal())->fetch();
    }

    protected function downloadMock()
    {
        return new Download($this->createMock(AvailableAppsRepository::class));
    }

    protected function checkPredictionsWithApp(ProphecyInterface $prophecy)
    {
        $this->downloadMock()->withApp($prophecy->reveal());
    }
}
