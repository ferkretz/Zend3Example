<?php

namespace Application\Service;

use Application\Entity\Processor;

// The ProcessorManager service is responsible for adding new processor.
class ProcessorManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    // This method adds a new processor.
    public function addNewProcessor($data) {
        // Create new Processor entity.
        $processor = new Processor();
        $processor->setName($data['name']);
        $processor->setDescription($data['description']);

        // Add the entity to entity manager.
        $this->entityManager->persist($processor);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function updateProcessor($processor, $data) {
        $processor->setName($data['name']);
        $processor->setDescription($data['description']);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeProcessor($processor) {
        $this->entityManager->remove($processor);

        $this->entityManager->flush();
    }

}
