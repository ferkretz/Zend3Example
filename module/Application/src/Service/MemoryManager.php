<?php

namespace Application\Service;

use Application\Entity\Memory;

// The MemoryManager service is responsible for adding new memory.
class MemoryManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    // This method adds a new memory.
    public function addNewMemory($data) {
        // Create new Memory entity.
        $memory = new Memory();
        $memory->setName($data['name']);
        $memory->setDimm($data['dimm']);
        $memory->setDescription($data['description']);

        // Add the entity to entity manager.
        $this->entityManager->persist($memory);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function updateMemory($memory, $data) {
        $memory->setName($data['name']);
        $memory->setDimm($data['dimm']);
        $memory->setDescription($data['description']);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeMemory($memory) {
        $this->entityManager->remove($memory);

        $this->entityManager->flush();
    }

}
