<?php

namespace yuki\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class FailedToVerifyHashException extends Exception implements ExceptionInterface
{
    /**
     * @var string
     */
    public $packageFilename;

    /**
     * @var string
     */
    public $hashOfLocalPackage;

    /**
     * @var string
     */
    public $reportedHash;

    /**
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     * @param string          $packageFilename
     * @param string          $hashOfLocalPackage
     * @param string          $reportedHash
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null, $packageFilename, $hashOfLocalPackage, $reportedHash)
    {
        $this->packageFilename = $packageFilename;
        $this->hashOfLocalPackage = $hashOfLocalPackage;
        $this->reportedHash = $reportedHash;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        Log::warning("Failed to verify $this->packageFilename. Expected $this->reportedHash instead of $this->hashOfLocalPackage");
    }
}
