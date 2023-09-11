<?php

namespace CloudCastle\Config;

use CloudCastle\AbstractClasses\Singleton;

abstract class AbstractConfig extends Singleton
{
    private string|null $path = null;

    final public function setPath(string $dir = ''): static
    {
        $this->path = realpath($dir);

        return $this;
    }

    final public function getPath(): string|null
    {
        return $this->path;
    }
}