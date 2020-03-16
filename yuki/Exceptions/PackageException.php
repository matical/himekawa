<?php

namespace yuki\Exceptions;

use Exception;
use yuki\Facades\Apk;

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
     * @param string $message
     * @param string $package
     * @param int    $versionCode
     */
    public function __construct(string $message, $package, $versionCode)
    {
        $this->package = $package;
        $this->versionCode = $versionCode;
    }

    /**
     * @param $package
     * @param $version
     * @return static
     */
    public static function AlreadyExists($package, $version)
    {
        return new static(sprintf('%s already exists.', Apk::resolveApkFilename($package, $version)), $package, $version);
    }
}
