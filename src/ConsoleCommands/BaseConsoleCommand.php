<?php declare(strict_types=1);

namespace Mimi\ConsoleCommands;

use Mimi\ConsoleCommands\Output\ConsoleCommandOutput;

abstract class BaseConsoleCommand
{
    abstract public function getConsoleCommandName() : string;

    public function getConsoleCommandAliases() : array
    {
        return [];
    }

    public function getConsoleCommandDescription(): string
    {
        return $this->getConsoleCommandName();
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function isInternal(): bool
    {
        return false;
    }

    public function run(ConsoleCommandOutput $output): int
    {
        return 0;
    }

}