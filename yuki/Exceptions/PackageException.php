<?php

namespace yuki\Exceptions;

use Exception;
use Throwable;
use yuki\Scrapers\Store\StoreApp;

class PackageException extends Exception implements ExceptionInterface
{
    /**
     * Name of the existing file.
     *
     * @var string
     */
    public $package;

    /**
     * @var int
     */
    public $versionCode;

    /**
     * PackageAlreadyExistsException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     * @param string          $package
     * @param int             $versionCode
     */
    public function __construct(string $message, int $code, ?Throwable $previous, $package, $versionCode)
    {
        $this->package = $package;
        $this->versionCode = $versionCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param \yuki\Scrapers\Store\StoreApp $storeApp
     * @return static
     */
    public static function AlreadyExists(StoreApp $storeApp)
    {
        $message = sprintf('%s already exists.', $storeApp->relativePath());

        return new static($message, 0, null, $storeApp->getPackageName(), $storeApp->getVersionCode());
    }
}
