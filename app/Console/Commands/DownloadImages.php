<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use Illuminate\Console\Command;
use ksmz\NanaLaravel\NanaManager;
use Symfony\Component\Process\Process;
use yuki\Command\HasPrettyProgressBars;
use yuki\Repositories\DetailsRepository;

class DownloadImages extends Command
{
    use HasPrettyProgressBars;

    /**
     * App icons' identified by GP's response.
     */
    const ICON_IMAGE = 4;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:fetch-images 
                            {--O|optimize : Optimize app icons after download (Requires optipng to be accessible from path) }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches app icons for watched apps';

    /**
     * @var \yuki\Repositories\DetailsRepository
     */
    protected $details;

    /**
     * @var \ksmz\NanaLaravel\NanaManager
     */
    protected $nana;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Repositories\DetailsRepository $details
     * @param \ksmz\NanaLaravel\NanaManager        $nana
     */
    public function __construct(DetailsRepository $details, NanaManager $nana)
    {
        parent::__construct();

        $this->details = $details;
        $this->nana = $nana;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $imagesToDownload = $this->peekImages(WatchedApp::pluck('package_name'));
        $this->fetchImages($imagesToDownload);
        if ($this->option('optimize')) {
            $this->runOptimizeCommand();
        }
    }

    /**
     * @param $packages
     * @return array
     */
    public function peekImages($packages)
    {
        $imagesToDownload = [];
        $bar = $this->newProgressBar($packages);

        foreach ($packages as $package) {
            $bar->setMessage("Fetching details for $package");

            $details = $this->details->getDetailsInfo($package);
            $imagesToDownload[$package] = $this->pluckImageUrl($details->image);

            $bar->advance();
        }

        $this->output->newLine(2);

        return $imagesToDownload;
    }

    /**
     * @param $images
     * @return mixed|string
     */
    protected function pluckImageUrl($images)
    {
        return collect($images)->firstWhere('imageType', self::ICON_IMAGE)->imageUrl ?? '';
    }

    /**
     * @param array $imagesToDownload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function fetchImages(array $imagesToDownload)
    {
        $bar = $this->newProgressBar($imagesToDownload);

        foreach ($imagesToDownload as $package => $imageUrl) {
            $bar->setMessage("Downloading $package's icon");

            $this->nana->faucet('image')
                       ->get($imageUrl)
                       ->store("$package.png");

            $bar->advance();
        }
    }

    protected function runOptimizeCommand()
    {
        $path = config('filesystems.disks.images.root');

        // Process will automatically kill/cleanup optipng if this (apk:fetch-images) gets ctrl-c'd
        $optipng = new Process("optipng $path/*.png");

        $optipng->start();
        $optipng->wait(function ($type, $buffer) {
            if (str_contains($buffer, 'assets')) {
                $this->line($buffer);
            }
        });
    }
}
