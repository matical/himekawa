<?php

namespace yuki\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class FailedToVerifyHashException extends Exception implements ExceptionInterface
{
    /**
     * @var
     */
    public $packageName;

    /**
     * @var
     */
    public $hashOfLocalPackage;

    /**
     * @var
     */
    public $reportedHash;

    /**
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     * @param                 $packageName
     * @param                 $hashOfLocalPackage
     * @param                 $reportedHash
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null, $packageName, $hashOfLocalPackage, $reportedHash)
    {
        $this->packageName = $packageName;
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
        Log::warning("Failed to verify $this->packageName. Expected $this->reportedHash instead of $this->hashOfLocalPackage");
    }
}
