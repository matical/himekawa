<?php

namespace yuki\Import;

use ksmz\json\Json as JsonDecoder;

class Json implements Configurable
{
    public function getFilePath(): string
    {
        return resource_path('apps.json');
    }

    public function serialize(string $config): array
    {
        return JsonDecoder::decode($config, true)['apps'];
    }
}
