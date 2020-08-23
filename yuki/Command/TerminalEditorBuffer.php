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

    public function __construct($editor = null)
    {
        $this->fileResource = tmpfile();
        $this->fileLocation = stream_get_meta_data($this->fileResource)['uri'];
        $this->editor = $editor ?? env('EDITOR', 'vim');
    }

    public function setInitialText($text)
    {
        $this->writeToBuffer($text);

        return $this;
    }

    /**
     * @return self
     */
    public function prompt()
    {
        $this->output = $this->launchEditorAndFetchBuffer($this->buildProcess());
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
        $process->mustRun(null, [
            'EDITOR'        => $this->editor,
            'FILE_LOCATION' => $this->fileLocation,
        ]);

        return $this->getFileContents($this->fileLocation);
    }

    /**
     * @return \Symfony\Component\Process\Process
     */
    protected function buildProcess()
    {
        $process = Process::fromShellCommandline('"$EDITOR" "$FILE_LOCATION"');
        $process->setTty(true);
        $process->setTimeout(3600);

        return $process;
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
