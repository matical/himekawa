<?php

namespace yuki\Foundation;

use Exception;
use ZipArchive;

class Zipper
{
    protected ZipArchive $zip;

    protected string $archivePath;

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    public function create(string $archiveName)
    {
        $this->archivePath = $archiveName;

        if ($this->zip->open($this->archivePath, ZipArchive::CREATE) !== true) {
            throw new Exception('Failed to create zip');
        }

        return $this;
    }

    /**
     * @param string $nameInArchive Name of the file in the archive
     * @param string $pathToFile    Path to the file
     * @return self
     * @throws \Exception
     */
    public function add(string $nameInArchive, string $pathToFile)
    {
        if (! file_exists($pathToFile)) {
            $this->cleanup();

            throw new Exception("{$pathToFile} does not exist");
        }

        if (! $this->zip->addFile($pathToFile, $nameInArchive)) {
            $this->cleanup();

            throw new Exception("Failed to add {$pathToFile} to archive");
        }

        $this->zip->setCompressionName($nameInArchive, ZipArchive::CM_STORE);

        return $this;
    }

    public function build()
    {
        if (! $this->zip->close()) {
            $this->cleanup();

            throw new Exception('Failed to save archive');
        }

        return $this->archivePath;
    }

    protected function cleanup()
    {
        return unlink($this->archivePath);
    }
}
