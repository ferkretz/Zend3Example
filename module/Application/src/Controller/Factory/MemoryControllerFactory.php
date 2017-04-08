<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\MemoryController;
use Application\Service\MemoryManager;

/**
 * This is the factory for MemoryController.
 */
class MemoryControllerFactory implements FactoryInterface {

    /**
     * 
     * @param ContainerInterface $container
     * @param type $requestedName
     * @param array $options
     * @return MemoryController The MemoryContainer.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $memoryManager = $container->get(MemoryManager::class);
        $translator = $container->get('Zend\Mvc\I18n\Translator');

        return new MemoryController($entityManager, $memoryManager, $translator);
    }

}
