<?php

namespace yuki\Exceptions;

use Exception;
use Throwable;

class PackageAlreadyExistsException extends Exception implements ExceptionInterface
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
    public function __construct(string $message, int $code, Throwable $previous = null, $package, $versionCode)
    {
        $this->package = $package;
        $this->versionCode = $versionCode;
    }
}
