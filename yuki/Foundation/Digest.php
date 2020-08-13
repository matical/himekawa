<?php

namespace yuki\Foundation;

class Digest
{
    public function unescape($str)
    {
        return str_replace(['-', '_'], ['+', '/'], $str);
    }

    public function decode($str)
    {
        return bin2hex(base64_decode($this->unescape($str)));
    }
}
