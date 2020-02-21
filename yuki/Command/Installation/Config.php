<?php

namespace yuki\Command\Installation;

class Config
{
    /**
     * @var array
     */
    protected $buffer;

    /**
     * @var array
     */
    protected $changeBuffer;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->buffer = $this->getConfigFile();
    }

    public function merge(int $lineNumber, string $key, $content)
    {
        $this->changeBuffer[$lineNumber - 1] = "$key=$content";

        return $this;
    }

    /**
     * @return self
     */
    public function commit()
    {
        $this->buffer = array_replace($this->buffer, $this->changeBuffer);

        return $this;
    }

    /**
     * @param string $filename
     * @return false|int
     */
    public function save(string $filename = '.env')
    {
        $retVal = file_put_contents($filename, implode("\n", $this->buffer));
        $this->flushBuffer();

        return $retVal;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function changes()
    {
        return collect($this->changeBuffer);
    }

    protected function flushBuffer()
    {
        $this->changeBuffer = [];
    }

    /**
     * @param string $file
     * @return array
     */
    protected function getConfigFile(string $file = '.env.example')
    {
        return explode("\n", file_get_contents($file));
    }
}
