<?php declare(strict_types=1);

namespace Mimi;

use Dotenv\Dotenv;

final class Mimi
{
    public static function initialize(): void {
        (Dotenv::createUnsafeMutable(getcwd() . DIRECTORY_SEPARATOR))->safeLoad();
        self::checkFolders();
    }

    private static function checkFolders()
    {
        if (!is_dir($concurrentDirectory = getcwd() . DIRECTORY_SEPARATOR . 'config')) {
            if (!mkdir($concurrentDirectory) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
            file_put_contents(getcwd() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.yaml', "imports:\n  - 'console_commands.yaml'\n\nservices:\n");
            file_put_contents(getcwd() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'console_commands.yaml', "services:\n");
        }
    }
}