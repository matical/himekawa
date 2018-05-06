<?php

namespace himekawa\Console\Commands;

use yuki\Clients\Image;
use himekawa\WatchedApp;
use Illuminate\Console\Command;
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
     * @var \yuki\Scrapers\Details
     */
    protected $details;

    /**
     * @var \yuki\Clients\Image
     */
    protected $image;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Repositories\DetailsRepository $details
     * @param \yuki\Clients\Image                  $image
     */
    public function __construct(DetailsRepository $details, Image $image)
    {
        parent::__construct();

        $this->details = $details;
        $this->image = $image;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->configureImagePath();

        $imagesToDownload = $this->peekImages(WatchedApp::pluck('package_name'));

        $this->output->newLine(2);

        $this->fetchImages($imagesToDownload);
    }

    protected function configureImagePath()
    {
        $this->image->setImagePath($this->option('image') ?? config('himekawa.paths.app_images'));
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

            $this->image->fetchAndStore($imageUrl, $package);

            $bar->advance();
        }
    }
}
