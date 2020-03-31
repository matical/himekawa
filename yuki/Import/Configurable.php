<?php

namespace yuki\Import;

interface Configurable
{
    public function getFilePath(): string;

    public function serialize(string $config): array;
}
