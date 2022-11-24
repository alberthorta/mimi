<?php declare(strict_types=1);

namespace Mimi\ConsoleCommands\Output;

class ConsoleCommandOutput
{
    public const DEF = "\e[39m";
    public const BLACK = "\e[30m";
    public const RED = "\e[31m";
    public const GREEN = "\e[32m";
    public const YELLOW = "\e[33m";
    public const BLUE = "\e[34m";
    public const MAGENTA = "\e[35m";
    public const CYAN = "\e[36m";
    public const LIGHT_GRAY = "\e[37m";
    public const DARK_GRAY = "\e[90m";
    public const LIGHT_RED = "\e[91m";
    public const LIGHT_GREEN = "\e[92m";
    public const LIGHT_YELLOW = "\e[93m";
    public const LIGHT_BLUE = "\e[94m";
    public const LIGHT_MAGENTA = "\e[95m";
    public const LIGHT_CYAN = "\e[96m";
    public const WHITE = "\e[97m";

    public function write(string $string = ""): void {
        echo($string);
    }

    public function writeln(string $string = ""): void {
        echo($string."\n");
    }
}