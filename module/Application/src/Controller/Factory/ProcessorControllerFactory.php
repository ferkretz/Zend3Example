<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ProcessorController;
use Application\Service\ProcessorManager;

/**
 * This is the factory for ProcessorController.
 */
class ProcessorControllerFactory implements FactoryInterface {

    /**
     * 
     * @param ContainerInterface $container
     * @param type $requestedName
     * @param array $options
     * @return ProcessorController The ProcessorContainer.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $processorManager = $container->get(ProcessorManager::class);
        $translator = $container->get('Zend\Mvc\I18n\Translator');

        return new ProcessorController($entityManager, $processorManager, $translator);
    }

}
