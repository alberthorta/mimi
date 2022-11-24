<?php declare(strict_types=1);

namespace Mimi\Services;

use Mimi\Exceptions\FrameworkException;
use Mimi\Mimi;
use Mimi\ValueObjects\CommandServiceDefinitionVO;
use Mimi\ValueObjects\ServiceDefinitionFactory;
use Mimi\ValueObjects\ServiceDefinitionVO;

final class ServiceContainer
{
    /** @var ServiceContainer|null */
    private static $me = null;

    /** @var ServiceDefinitionVO[] */
    private array $instances;

    private function __construct() {
        $this->parseFile(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'mimi_services.yaml'));
        if(file_exists(getcwd() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.yaml')) {
            $this->parseFile(file_get_contents(getcwd() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.yaml'));
        }
    }

    /**
     * @param string $serviceName
     * @return void
     * @throws FrameworkException
     */
    public function getInstanceOf(string $serviceName): object {
        if(!array_key_exists($serviceName, $this->instances)) {
            throw new FrameworkException('Service '.$serviceName.' is not defined');
        }
        return $this->instances[$serviceName]->getInstance($this);
    }

    /**
     * @return CommandServiceDefinitionVO[]
     */
    public function getConsoleServices() : array
    {
        $consoleServices = array_values(
            array_filter(
                $this->instances,
                static function(ServiceDefinitionVO $service) {
                    return in_array('CONSOLE', $service->getTags(), true);
                }
            )
        );

        $consoleServices = array_combine(
            array_map(
                function(CommandServiceDefinitionVO $commandService) {
                    return $commandService->getCommandName();
                },
                $consoleServices
            ),
            $consoleServices
        );

        ksort($consoleServices);

        return $consoleServices;
    }

    public static function get(): ServiceContainer
    {
        if(!self::$me) {
            self::$me = new self();
        }
        return self::$me;
    }

    /**
     * @param $param
     * @return array|mixed|object|string|void
     * @throws FrameworkException
     */
    public function parseParams($param)
    {
        if(is_string($param) && $param !== '') {
            switch($param[0]) {
                case '@':
                    return $this->getInstanceOf(substr($param, 1));
                case '#':
                    return $_ENV[substr($param, 1)];
            }
        }
        if(is_array($param) && count($param)>0) {
            foreach($param as $pkey => &$pparam) {
                $pparam = $this->parseParams($pparam);
            }
        }
        return $param;
    }

    private function parseFile(string $fileContents): void
    {
        $configuration = yaml_parse($fileContents);

        foreach(($configuration['services'] ?? []) as $serviceName => $serviceParams) {
            $arguments = array_key_exists('arguments', ($serviceParams ?? [])) ? $serviceParams['arguments'] : [];
            $this->instances[$serviceName] = ServiceDefinitionFactory::create(
                $serviceName,
                $arguments,
                array_key_exists('tags', ($serviceParams ?? [])) ? $serviceParams['tags'] : []
            );
        }

        foreach(($configuration['imports'] ?? []) as $fileToImport) {
            $this->parseFile(file_get_contents(getcwd() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $fileToImport));
        }
    }
}