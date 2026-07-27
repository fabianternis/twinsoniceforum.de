<?php

namespace App;

class Config
{
    private static ?array $settings = null;

    public static function load(): void
    {
        if (self::$settings !== null) {
            return;
        }

        self::$settings = [
            'APP_NAME' => 'Twins on Ice Community Forum',
            'APP_ENV' => 'production',
            'APP_URL' => 'https://twinsoniceforum.dnbx.de',
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '3306',
            'DB_NAME' => 'twinsoniceforum',
            'DB_USER' => 'root',
            'DB_PASS' => '',
            'SECRET_KEY' => 'twinsonice_ice_skating_secret_key_2026_x89f',
        ];

        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                if (str_contains($line, '=')) {
                    list($key, $val) = explode('=', $line, 2);
                    $key = trim($key);
                    $val = trim($val, " \t\n\r\0\x0B\"'");
                    self::$settings[$key] = $val;
                }
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();
        return self::$settings[$key] ?? $default;
    }
}
