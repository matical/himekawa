<?php

namespace yuki\Badging;

class Parser
{
    /**
     * @param $dump
     * @return array
     */
    public function parse($dump)
    {
        $tokens = $this->splitTokens($dump);
        $firstLine = $this->fetchFirstLine($tokens);
        $sliced = $this->popPackage($firstLine);

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
    protected function splitTokens($dump): array
    {
        return explode("\n", $dump);
    }

    /**
     * The first line contains everything we need.
     *
     * @param array $splittedLines
     * @return array
     */
    protected function fetchFirstLine(array $splittedLines): array
    {
        return explode(' ', $splittedLines[0]);
    }

    /**
     * Get rid of "package:".
     *
     * @param array $delimited
     * @return array
     */
    protected function popPackage(array $delimited): array
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
