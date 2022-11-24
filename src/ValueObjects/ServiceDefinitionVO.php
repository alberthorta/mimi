<?php declare(strict_types=1);

namespace Mimi\ValueObjects;

use Mimi\ConsoleCommands\BaseConsoleCommand;
use Mimi\Services\ServiceContainer;

class ServiceDefinitionVO
{
    /** @var string */
    private string $serviceClass;

    /** @var callable|mixed */
    private $instance;

    /** @var bool */
    private bool $isLoaded;

    /** @var array */
    private array $tags;

    public function __construct(string $serviceClass, array $arguments, array $tags) {
        $this->serviceClass = $serviceClass;
        if(!in_array('CONSOLE', $tags, true)) {
            $rf = new \ReflectionClass($serviceClass);
            if($rf->isSubclassOf(BaseConsoleCommand::class)) {
                $tags[] = 'CONSOLE';
            }

        }
        $this->tags = $tags;
        $this->isLoaded = false;
        $this->instance = static function(ServiceContainer $serviceContainer) use ($serviceClass, $arguments) {
            $parsedParams = array_map(
                [$serviceContainer, 'parseParams'],
                $arguments ?? []
            );
            return new $serviceClass(...$parsedParams);
        };
    }

    public function getServiceClass(): string
    {
        return $this->serviceClass;
    }

    public function getInstance(ServiceContainer $serviceContainer, bool $loadInstance = true): mixed
    {
        if(!$this->isLoaded && $loadInstance) {
            $this->isLoaded = true;
            $this->instance = ($this->instance)($serviceContainer);
        }
        return $this->instance;
    }

    public function getTags() : array
    {
        return $this->tags;
    }
}