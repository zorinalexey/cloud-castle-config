<?php

namespace CloudCastle\Config;

final class Config extends AbstractConfig
{
    private static array $config = [];

    public function get(string $name, mixed $default = []): mixed
    {
        $filePath = $this->getFilePath($name);

        return self::$config[$filePath] ?? $default;
    }

    private function getFilePath(string $name): string
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . $name . '.php';
    }

    public function loadPath(): void
    {
        $files = scandir($this->getPath());

        foreach ($files as $file) {
            $path = basename(
                str_replace(
                    '.php',
                    '',
                    $this->getFilePath(str_replace('.php', '', $file))
                )
            );
            $this->load($path);
        }
    }


    /**
     * @throws ConfigException
     */
    public function load(string $name): self
    {
        $file = $this->getFilePath($name);

        if (file_exists($file)) {
            self::$config[$file] = require $file;
        }

        return $this;
    }
}