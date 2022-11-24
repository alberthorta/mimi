<?php declare(strict_types=1);

namespace Mimi\ValueObjects;

use Mimi\ConsoleCommands\BaseConsoleCommand;

class ServiceDefinitionFactory
{
    public static function create(string $serviceClass, array $arguments, array $tags): ServiceDefinitionVO
    {
        $rf = new \ReflectionClass($serviceClass);
        if($rf->isSubclassOf(BaseConsoleCommand::class)) {
            $tags = array_unique(array_merge($tags, ['CONSOLE']));
        }

        if(in_array('CONSOLE', $tags, true)) {
            $consoleCommand = $rf->newInstanceWithoutConstructor();
            /** @var BaseConsoleCommand $consoleCommand */
            return new CommandServiceDefinitionVO(
                $serviceClass,
                $arguments,
                $tags,
                $consoleCommand->getConsoleCommandName(),
                $consoleCommand->getConsoleCommandAliases(),
                $consoleCommand->getConsoleCommandDescription(),
                $consoleCommand->isPublic(),
                $consoleCommand->isInternal()
            );
        }

        return new ServiceDefinitionVO(
            $serviceClass,
            $arguments,
            $tags
        );
    }
}