<?php


namespace SergeiKukhariev\ApiSkeleton\Factories;


use Psr\Container\ContainerInterface;
use SergeiKukhariev\ApiSkeleton\ErrorHandlers\ApiErrorHandler;

class ApiErrorHandlerFactory
{
    private $container;

    public function __invoke(ContainerInterface $container)
    {
        $this->container = $container;
        $config = $this->container->has('config') ? $this->container->get('config') : [];

        return new ApiErrorHandler($config['debug'] ?? false);
    }
}