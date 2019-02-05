<?php

namespace yuki\Exceptions;

use Exception;
use Throwable;
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
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     * @param string          $package
     * @param int             $versionCode
     */
    public function __construct(string $message, int $code, Throwable $previous, $package, $versionCode)
    {
        $this->package = $package;
        $this->versionCode = $versionCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param $package
     * @param $version
     * @return static
     */
    public static function AlreadyExists($package, $version)
    {
        return new static(Apk::resolveApkFilename($package, $version) . ' already exists.', 0, null, $package, $version);
    }
}
