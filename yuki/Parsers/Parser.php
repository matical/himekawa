<?php

namespace yuki\Parsers;

class Parser
{
    /**
     * @param $dump
     * @return array
     */
    public function parse($dump)
    {
        $splittedLines = $this->splitLines($dump);
        $delimited = $this->delimit($splittedLines);
        $sliced = $this->fetchFirstLine($delimited);

        $packages = [];

        foreach ($sliced as $slice) {
            [$key, $value] = explode('=', $slice);
            $stripped = $this->stripQuotes($value);

            $packages[$key] = $this->parseAndCast($stripped);
        }

        return $packages;
    }

    /**
     * @param $dump
     * @return array
     */
    protected function splitLines($dump): array
    {
        return explode("\n", $dump);
    }

    /**
     * @param array $splittedLines
     * @return array
     */
    protected function delimit(array $splittedLines): array
    {
        return explode(' ', $splittedLines[0]);
    }

    /**
     * @param array $delimited
     * @return array
     */
    protected function fetchFirstLine(array $delimited): array
    {
        return array_slice($delimited, 1);
    }

    /**
     * Strip out unnecessary quotes.
     *
     * @param $quotedString
     * @return mixed
     */
    protected function stripQuotes($quotedString)
    {
        return str_replace('\'', '', $quotedString);
    }

    /**
     * @param $stripped
     * @return int|string
     */
    protected function parseAndCast($stripped)
    {
        return is_numeric($stripped) ? (int) $stripped : $stripped;
    }
}
