<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ComputerController;
use Application\Service\ComputerManager;

/**
 * This is the factory for ComputerController.
 */
class ComputerControllerFactory implements FactoryInterface {

    /**
     * 
     * @param ContainerInterface $container
     * @param type $requestedName
     * @param array $options
     * @return ComputerController The ComputerContainer.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $computerManager = $container->get(ComputerManager::class);
        $translator = $container->get('Zend\Mvc\I18n\Translator');

        return new ComputerController($entityManager, $computerManager, $translator);
    }

}
