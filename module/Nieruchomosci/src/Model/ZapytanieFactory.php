<?php

namespace Nieruchomosci\Model;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ZapytanieFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new Zapytanie($container->get('config')['mail']);
    }
}