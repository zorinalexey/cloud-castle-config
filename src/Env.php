<?php

namespace CloudCastle\Config;

final class Env extends AbstractConfig
{
    private static array $envData = [];

    public function load(string $envFileName = '.env'): self
    {
        $file = $this->getPath() . DIRECTORY_SEPARATOR . $envFileName;
        $this->parse($file);

        if (self::$envData) {
            foreach (self::$envData as $name => $value) {
                putenv($name . '=' . $value);
            }
        }

        return $this;
    }

    private function parse(string $file): bool
    {
        if (file_exists($file)) {
            $data = explode("\n", file_get_contents($file));
            $pattern = '~^(?<name>\w+)(\s+)?=(\s+)?(")?(?<value>[^#"]+)(")?(\s+)?(#(.+))?$~ui';

            foreach ($data as $item) {
                if (preg_match($pattern, $item, $matches) && $value = $this->getValue($matches['value'])) {
                    $this->setEnvData($matches['name'], $value);
                }
            }

            return true;
        }

        return false;
    }

    private function getValue(string $value): string
    {
        $pattern = '~([\w\s]+)?(\${(?<var>[\w\s]+)})([\w\s])?~ui';

        if (preg_match_all($pattern, $value, $matches)) {
            foreach ($matches['var'] as $item) {
                $value = str_replace('${' . $item . '}', self::$envData[$item] ?? null, $value);
            }
        }

        return $value;
    }

    private function setEnvData(string $name, string $value): void
    {
        self::$envData[$name] = $value;
    }

    public function get(string $varName, string|bool|null $default = null): string|bool|null
    {
        if ($var = getenv($varName)) {
            return $var;
        }

        return $default;
    }
}

include_once 'helpers.php';