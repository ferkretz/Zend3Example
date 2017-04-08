<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\Criteria;
use Zend\View\Model\ViewModel;
use Application\Entity\Computer;
use Application\Entity\Processor;
use Application\Entity\Memory;
use Application\Form\ComputerForm;

class ComputerController extends AbstractActionController {

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Computer manager.
     * @var Application\Service\ComputerManager
     */
    private $computerManager;

    /**
     * Translator.
     * @var Zend\Mvc\I18n\Translator
     */
    private $translator;

    /**
     * Constructor method is used to inject dependencies to the controller.
     *
     * @param Doctrine\ORM\EntityManager $entityManager Entity manager.
     * @param ComputerManager $computerManager Computer manager.
     * @param Zend\Mvc\I18n\Translator $translator Translator.
     */
    public function __construct($entityManager, $computerManager, $translator) {
        $this->entityManager = $entityManager;
        $this->computerManager = $computerManager;
        $this->translator = $translator;
    }

    /**
     * List all Computers.
     */
    public function indexAction() {
        // Fetch all computers.
        $computers = $this->entityManager->getRepository(Computer::class)->findAll();

        // Render the view template. This is a short test, we dont use pagination.
        return new ViewModel([
            'computers' => $computers
        ]);
    }

    /**
     * Add new Computer.
     */
    public function addAction() {
        // Create new ProessorForm.
        $form = new ComputerForm($this->translator);

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            $add = $this->getRequest()->getPost('add', 'Cancel');
            if ($add == 'Cancel') {
                // Redirect to the Computers.
                return $this->redirect()->toRoute('computer');
            }
            // Get POST data and fill the new ComputerForm.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate the form.
            if ($form->isValid()) {
                $data = $form->getData();
                // Add new Computer to the database.
                $this->computerManager->addNewComputer($data);
                // Redirect to the Computers.
                return $this->redirect()->toRoute('computer');
            }
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * Update a Computer.
     */
    public function editAction() {
        // Create new ProessorForm.
        $form = new ComputerForm($this->translator);
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Computer from the database (404 message if not exists).
        $computer = $this->entityManager->getRepository(Computer::class)->findOneById($id);
        if ($computer == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            $edit = $this->getRequest()->getPost('edit', 'Cancel');
            if ($edit == 'Cancel') {
                // Redirect to the Computers.
                return $this->redirect()->toRoute('computer');
            }
            // Get POST data and fill the new ComputerForm.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate the form.
            if ($form->isValid()) {
                $data = $form->getData();
                // Delete memories when dimm changed
                if ($computer->getDimm() != $data['dimm']) {
                    $computer->deleteMemories();
                }
                // Update Computer.
                $this->computerManager->updateComputer($computer, $data);
                // Redirect to the Computers.
                return $this->redirect()->toRoute('computer');
            }
        } else {
            // Original Computer.
            $data = [
                'dimm' => $computer->getDIMM(),
                'name' => $computer->getName(),
                'description' => $computer->getDescription(),
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'computer' => $computer
        ]);
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Computer from the database (404 message if not exists).
        $computer = $this->entityManager->getRepository(Computer::class)->findOneById($id);
        if ($computer == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm delete.
            $delete = $this->getRequest()->getPost('delete', 'Cancel');
            if ($delete == 'OK') {
                $this->computerManager->removeComputer($computer);
            }
            // Redirect to the Computers.
            return $this->redirect()->toRoute('computer');
        }

        // Render the view template.
        return new ViewModel([
            'computer' => $computer
        ]);
    }

    public function processorDeleteAction($computer) {
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm set.
            $delete = $this->getRequest()->getPost('delete', 'Cancel');
            if ($delete == 'Ok') {
                $computer->deleteProcessor();
                $this->computerManager->updateComputer($computer, NULL);
            }
            // Redirect to the Computers.
            return $this->redirect()->toRoute('computer', ['action' => 'edit', 'id' => $computer->getId()]);
        }

        // Set and render the view template.
        $view = new ViewModel([]);
        $view->setTemplate('application/computer/processor/delete');
        return $view;
    }

    public function processorSetAction($computer) {
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm set.
            $set = $this->getRequest()->getPost('set', 'Cancel');
            if ($set == 'Ok') {
                $processorId = $this->getRequest()->getPost('processor-id');
                $processor = $this->entityManager->getRepository(Processor::class)->findOneById($processorId);
                $computer->setProcessor($processor);
                $this->computerManager->updateComputer($computer);
            }
            // Redirect to the Computers.
            return $this->redirect()->toRoute('computer', ['action' => 'edit', 'id' => $computer->getId()]);
        }

        // Fetch all processors.
        $processors = $this->entityManager->getRepository(Processor::class)->findAll();

        // Set and render the view template.
        $view = new ViewModel([
            'computer' => $computer,
            'processors' => $processors
        ]);
        $view->setTemplate('application/computer/processor/set');
        return $view;
    }

    public function processorAction() {
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Computer from the database (404 message if not exists).
        $computer = $this->entityManager->getRepository(Computer::class)->findOneById($id);
        if ($computer == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $subAction = $this->params()->fromRoute('sub_action');

        switch ($subAction) {
            case 'set':
                return $this->processorSetAction($computer);
            case 'delete':
                return $this->processorDeleteAction($computer);
        }

        // Redirect to the computer.
        return $this->redirect()->toRoute('computer', ['action' => 'edit', 'id' => $id]);
    }

    public function memoryDeleteAction($computer) {
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm set.
            $delete = $this->getRequest()->getPost('delete', 'Cancel');
            if ($delete == 'Ok') {
                $subId = (int) $this->params()->fromRoute('sub_id');
                // Really need update?
                if ($computer->deleteMemory($subId)) {
                    $this->computerManager->updateComputer($computer, NULL);
                }
            }
            // Redirect to the Computers.
            return $this->redirect()->toRoute('computer', ['action' => 'edit', 'id' => $computer->getId()]);
        }

        // Set and render the view template.
        $view = new ViewModel([]);
        $view->setTemplate('application/computer/memory/delete');
        return $view;
    }

    public function memoryAddAction($computer) {
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm set.
            $set = $this->getRequest()->getPost('set', 'Cancel');
            if ($set == 'Ok') {
                $memoryId = $this->getRequest()->getPost('memory-id');
                $memory = $this->entityManager->getRepository(Memory::class)->findOneById($memoryId);
                $computer->addMemory($memory);
                $this->computerManager->updateComputer($computer);
            }
            // Redirect to the Computers.
            return $this->redirect()->toRoute('computer', ['action' => 'edit', 'id' => $computer->getId()]);
        }

        // Fetch memories of computer. Filter out the other dimms, and used modules.
        $criteria = new Criteria();
        $memoryIds = $computer->getMemoryIds();
        $dimm = $computer->getDimm();
        if (empty($memoryIds)) {
            $criteria->where($criteria->expr()->eq('dimm', $dimm));
        } else {
            $criteria->where($criteria->expr()->andX(
                            $criteria->expr()->eq('dimm', $dimm), $criteria->expr()->notIn('id', $memoryIds)
            ));
        }
        $memories = $this->entityManager->getRepository(Memory::class)->matching($criteria);

        // Set and render the view template.
        $view = new ViewModel([
            'computer' => $computer,
            'memories' => $memories
        ]);
        $view->setTemplate('application/computer/memory/add');
        return $view;
    }

    public function memoryAction() {
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Computer from the database (404 message if not exists).
        $computer = $this->entityManager->getRepository(Computer::class)->findOneById($id);
        if ($computer == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $subAction = $this->params()->fromRoute('sub_action');

        switch ($subAction) {
            case 'add':
                return $this->memoryAddAction($computer);
            case 'delete':
                return $this->memoryDeleteAction($computer);
        }

        // Redirect to the computer.
        return $this->redirect()->toRoute('computer', ['action' => 'edit', 'id' => $id]);
    }

}
