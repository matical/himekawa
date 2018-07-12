<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use Illuminate\Console\Command;
use ksmz\NanaLaravel\NanaManager;
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
    protected $signature = 'apk:fetch-images {--image=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches app icons for watched apps';

    /**
     * @var DetailsRepository
     */
    protected $details;

    /**
     * @var \yuki\Clients\Image
     */
    protected $image;

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
     */
    public function handle()
    {
        $imagesToDownload = $this->peekImages(WatchedApp::pluck('package_name'));
        $this->fetchImages($imagesToDownload);
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
}
