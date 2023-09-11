<?php

use CloudCastle\Config\Config;
use CloudCastle\Config\Env;

function config(string $name, mixed $default = []): mixed
{
    return Config::getInstance()->get($name, $default);
}

function env(string $varName, string|bool|null $default = null): bool|string|null
{
    return Env::getInstance()->get($varName, $default);
}