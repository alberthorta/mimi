<?php declare(strict_types=1);

namespace Mimi\ConsoleCommands;

use Mimi\ConsoleCommands\Output\ConsoleCommandOutput;
use Mimi\Services\ServiceContainer;
use Mimi\ValueObjects\CommandServiceDefinitionVO;

class ListConsoleCommandsCommand extends BaseConsoleCommand
{
    public function getConsoleCommandName(): string
    {
        return "list";
    }

    public function getConsoleCommandAliases(): array
    {
        return [""];
    }

    public function getConsoleCommandDescription(): string
    {
        return "The screen that you're seeing right now 😬";
    }

    public function isInternal(): bool
    {
        return true;
    }

    private function outputDescription(ConsoleCommandOutput $output, CommandServiceDefinitionVO $consoleCommand): void
    {
        $output->write(ConsoleCommandOutput::YELLOW);
        $output->write("\t". $consoleCommand->getCommandName().' : ');
        $output->write(ConsoleCommandOutput::WHITE);
        $output->write($consoleCommand->getCommandDescription());
        $aliases = array_filter($consoleCommand->getCommandAliases());
        if(count($aliases) > 0) {
            $output->write(ConsoleCommandOutput::MAGENTA);
            $output->write(' (Aliases : '.implode(', ', $aliases).')');
        }
        $output->writeln(ConsoleCommandOutput::DEF);
    }

    public function run(ConsoleCommandOutput $output): int
    {
        $output->write(ConsoleCommandOutput::LIGHT_MAGENTA);
        $output->writeln(" __  __  ____  __  __  ____");
        $output->writeln("(  \/  )(_  _)(  \/  )(_  _)");
        $output->writeln(" )    (  _)(_  )    (  _)(_");
        $output->writeln("(_/\/\_)(____)(_/\/\_)(____)");
        $output->writeln("   Mimimal PHP framework");
        $output->writeln();
        $output->write(ConsoleCommandOutput::GREEN);
        $output->writeln("  Internal Commands :");
        foreach(ServiceContainer::get()->getConsoleServices() as $consoleCommand) {
            if(!$consoleCommand->isPublic() || !$consoleCommand->isInternal()) {
                continue;
            }
            $this->outputDescription($output, $consoleCommand);
        }
        $output->writeln();
        $output->write(ConsoleCommandOutput::GREEN);
        $output->writeln("  Available Commands :");
        foreach(ServiceContainer::get()->getConsoleServices() as $consoleCommand) {
            if(!$consoleCommand->isPublic() || $consoleCommand->isInternal()) {
                continue;
            }
            $this->outputDescription($output, $consoleCommand);
        }
        $output->writeln();
        return parent::run($output);
    }
}