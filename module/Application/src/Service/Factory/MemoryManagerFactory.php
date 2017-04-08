<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\MemoryManager;

/**
 * This is the factory for MemoryManager. Its purpose is to instantiate the
 * service.
 */
class MemoryManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Instantiate the service and inject dependencies
        return new MemoryManager($entityManager);
    }

}
