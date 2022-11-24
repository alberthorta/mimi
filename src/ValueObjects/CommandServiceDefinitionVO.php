<?php declare(strict_types=1);

namespace Mimi\ValueObjects;

class CommandServiceDefinitionVO extends ServiceDefinitionVO
{
    /** @var string */
    private string $commandName;

    /** @var string[]  */
    private array $commandAliases;

    /** @var string */
    private string $commandDescription;

    /** @var bool */
    private bool $isPublic;

    /** @var bool */
    private bool $isInternal;

    public function __construct(string $serviceClass, array $arguments, array $tags, string $commandName, array $commandAliases = [], string $commandDescription = "", bool $isPublic = true, bool $isInternal = false)
    {
        parent::__construct($serviceClass, $arguments, $tags);
        $this->commandName = $commandName;
        $this->commandAliases = array_unique($commandAliases);
        $this->commandDescription = $commandDescription;
        $this->isPublic = $isPublic;
        $this->isInternal = $isInternal;
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @return string[]
     */
    public function getCommandAliases(): array
    {
        return $this->commandAliases;
    }

    public function getCommandDescription(): string
    {
        return $this->commandDescription;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function isInternal(): bool
    {
        return $this->isInternal;
    }
}