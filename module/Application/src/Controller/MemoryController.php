<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Memory;
use Application\Form\MemoryForm;

class MemoryController extends AbstractActionController {

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Memory manager.
     * @var Application\Service\MemoryManager
     */
    private $memoryManager;

    /**
     * Translator.
     * @var Zend\Mvc\I18n\Translator
     */
    private $translator;

    /**
     * Constructor method is used to inject dependencies to the controller.
     *
     * @param Doctrine\ORM\EntityManager $entityManager Entity manager.
     * @param MemoryManager $memoryManager Memory manager.
     * @param Zend\Mvc\I18n\Translator $translator Translator.
     */
    public function __construct($entityManager, $memoryManager, $translator) {
        $this->entityManager = $entityManager;
        $this->memoryManager = $memoryManager;
        $this->translator = $translator;
    }

    /**
     * List all Memories.
     */
    public function indexAction() {
        // Fetch all memories.
        $memories = $this->entityManager->getRepository(Memory::class)->findAll();

        // Render the view template. This is a short test, we dont use pagination.
        return new ViewModel([
            'memories' => $memories
        ]);
    }

    /**
     * Add new Memory.
     */
    public function addAction() {
        // Create new ProessorForm.
        $form = new MemoryForm($this->translator);

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            $add = $this->getRequest()->getPost('add', 'Cancel');
            if ($add == 'Cancel') {
                // Redirect to the Memories.
                return $this->redirect()->toRoute('memory');
            }
            // Get POST data and fill the new MemoryForm.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate the form.
            if ($form->isValid()) {
                $data = $form->getData();
                // Add new Memory to the database.
                $this->memoryManager->addNewMemory($data);
                // Redirect to the Memories.
                return $this->redirect()->toRoute('memory');
            }
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * Update a Memory.
     */
    public function editAction() {
        // Create new ProessorForm.
        $form = new MemoryForm($this->translator);
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Memory from the database (404 message if not exists).
        $memory = $this->entityManager->getRepository(Memory::class)->findOneById($id);
        if ($memory == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            $edit = $this->getRequest()->getPost('edit', 'Cancel');
            if ($edit == 'Cancel') {
                // Redirect to the Memories.
                return $this->redirect()->toRoute('memory');
            }
            // Get POST data and fill the new MemoryForm.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate the form.
            if ($form->isValid()) {
                $data = $form->getData();
                // Update Memory.
                $this->memoryManager->updateMemory($memory, $data);
                // Redirect to the Memories.
                return $this->redirect()->toRoute('memory');
            }
        } else {
            // Original Memory.
            $data = [
                'dimm' => $memory->getDIMM(),
                'name' => $memory->getName(),
                'description' => $memory->getDescription(),
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'memory' => $memory
        ]);
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Memory from the database (404 message if not exists).
        $memory = $this->entityManager->getRepository(Memory::class)->findOneById($id);
        if ($memory == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm delete.
            $delete = $this->getRequest()->getPost('delete', 'Cancel');
            if ($delete == 'Yes') {
                $this->memoryManager->removeMemory($memory);
            }
            // Redirect to the Memories.
            return $this->redirect()->toRoute('memory');
        }

        // Render the view template.
        return new ViewModel([
            'memory' => $memory
        ]);
    }

}
