<?php

namespace yuki\Clients;

use yuki\Facades\Http;

class Image
{
    /**
     * @var string
     */
    protected $imagePath;

    /**
     * @param string $imagePath
     */
    public function setImagePath(string $imagePath)
    {
        $this->imagePath = str_finish($imagePath, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $url
     * @param string $package
     */
    public function fetchAndStore($url, $package)
    {
        $this->buildClient()
             ->pokeOptions($this->configureClient($package))
             ->get($url);
    }

    /**
     * @return \yuki\Clients\PendingRequest
     */
    public function buildClient()
    {
        return Http::make();
    }

    /**
     * @param $package
     * @return array
     */
    public function configureClient($package)
    {
        return [
            'sink' => $this->imagePath . $this->resolveImagePath($package),
        ];
    }

    /**
     * @param $package
     * @return string
     */
    protected function resolveImagePath($package)
    {
        return sprintf('%s.png', $package);
    }
}
