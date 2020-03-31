<?php

namespace yuki\Import;

class Ini implements Configurable
{
    public function getFilePath(): string
    {
        return resource_path('apps.ini');
    }

    public function serialize(string $config): array
    {
        return parse_ini_file($config, true, INI_SCANNER_TYPED);
    }
}
