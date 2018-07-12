<?php

namespace yuki\Command;

use RuntimeException;
use Symfony\Component\Process\Process;

class TerminalEditorBuffer
{
    /**
     * @var string
     */
    protected $editor;

    /**
     * @var resource
     */
    protected $fileResource;

    /**
     * @var string
     */
    protected $fileLocation;

    /**
     * @var string
     */
    protected $output;

    public function __construct()
    {
        $this->initResources();
        $this->editor = env('EDITOR', 'vim');
    }

    public function initial($text)
    {
        $this->writeToBuffer($text);

        return $this;
    }

    /**
     * @return self
     */
    public function prompt()
    {
        $process = $this->buildProcess();
        $this->output = $this->launchEditorAndFetchBuffer($process);

        $this->cleanUp($this->fileResource);

        return $this;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    protected function initResources()
    {
        $this->fileResource = tmpfile();
        $this->fileLocation = stream_get_meta_data($this->fileResource)['uri'];
    }

    protected function writeToBuffer($text)
    {
        fwrite($this->fileResource, $text);
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     * @return bool|string
     */
    protected function launchEditorAndFetchBuffer(Process $process)
    {
        $process->mustRun();

        return $this->getFileContents($this->fileLocation);
    }

    /**
     * @return \Symfony\Component\Process\Process
     */
    protected function buildProcess()
    {
        return tap(new Process("vim {$this->fileLocation}"), function (Process $process) {
            $process->setTty(true);
            $process->setTimeout(3600);
        });
    }

    /**
     * @param string $location
     * @return bool|string
     */
    protected function getFileContents($location)
    {
        $buffer = file_get_contents($location);

        if ($buffer === false) {
            throw new RuntimeException('Could not get data from buffer output');
        }

        return $buffer;
    }

    protected function cleanUp($resource)
    {
        fclose($resource);
    }

    public function __destruct()
    {
        if (is_resource($this->fileResource)) {
            fclose($this->fileResource);
        }
    }
}
