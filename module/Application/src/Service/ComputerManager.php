<?php

namespace Application\Service;

use Application\Entity\Computer;

// The ComputerManager service is responsible for adding new computer.
class ComputerManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    // This method adds a new computer.
    public function addNewComputer($data) {
        // Create new Computer entity.
        $computer = new Computer();
        $computer->setName($data['name']);
        $computer->setDimm($data['dimm']);
        $computer->setDescription($data['description']);

        // Add the entity to entity manager.
        $this->entityManager->persist($computer);

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function updateComputer($computer, $data) {
        if (isset($data['name'])) {
            $computer->setName($data['name']);
        }
        if (isset($data['dimm'])) {
            $computer->setDimm($data['dimm']);
        }
        if (isset($data['description'])) {
            $computer->setDescription($data['description']);
        }

        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeComputer($computer) {
        $this->entityManager->remove($computer);

        $this->entityManager->flush();
    }

}
