<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Processor;
use Application\Form\ProcessorForm;

class ProcessorController extends AbstractActionController {

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Processor manager.
     * @var Application\Service\ProcessorManager
     */
    private $processorManager;

    /**
     * Translator.
     * @var Zend\Mvc\I18n\Translator
     */
    private $translator;

    /**
     * Constructor method is used to inject dependencies to the controller.
     *
     * @param Doctrine\ORM\EntityManager $entityManager Entity manager.
     * @param ProcessorManager $processorManager Processor manager.
     * @param Zend\Mvc\I18n\Translator $translator Translator.
     */
    public function __construct($entityManager, $processorManager, $translator) {
        $this->entityManager = $entityManager;
        $this->processorManager = $processorManager;
        $this->translator = $translator;
    }

    /**
     * List all Processors.
     */
    public function indexAction() {
        // Fetch all processors.
        $processors = $this->entityManager->getRepository(Processor::class)->findAll();

        // Render the view template. This is a short test, we dont use pagination.
        return new ViewModel([
            'processors' => $processors
        ]);
    }

    /**
     * Add new Processor.
     */
    public function addAction() {
        // Create new ProessorForm.
        $form = new ProcessorForm($this->translator);

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            $add = $this->getRequest()->getPost('add', 'Cancel');
            if ($add == 'Cancel') {
                // Redirect to the Processors.
                return $this->redirect()->toRoute('processor');
            }
            // Get POST data and fill the new ProcessorForm.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate the form.
            if ($form->isValid()) {
                $data = $form->getData();
                // Add new Processor to the database.
                $this->processorManager->addNewProcessor($data);
                // Redirect to the Processors.
                return $this->redirect()->toRoute('processor');
            }
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * Update a Processor.
     */
    public function editAction() {
        // Create new ProessorForm.
        $form = new ProcessorForm($this->translator);
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Processor from the database (404 message if not exists).
        $processor = $this->entityManager->getRepository(Processor::class)->findOneById($id);
        if ($processor == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            $edit = $this->getRequest()->getPost('edit', 'Cancel');
            if ($edit == 'Cancel') {
                // Redirect to the Processors.
                return $this->redirect()->toRoute('processor');
            }
            // Get POST data and fill the new ProcessorForm.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate the form.
            if ($form->isValid()) {
                $data = $form->getData();
                // Update Processor.
                $this->processorManager->updateProcessor($processor, $data);
                // Redirect to the Processors.
                return $this->redirect()->toRoute('processor');
            }
        } else {
            // Original Processor.
            $data = [
                'name' => $processor->getName(),
                'description' => $processor->getDescription(),
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'processor' => $processor
        ]);
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', -1);

        // Fetch Processor from the database (404 message if not exists).
        $processor = $this->entityManager->getRepository(Processor::class)->findOneById($id);
        if ($processor == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Confirm delete.
            $delete = $this->getRequest()->getPost('delete', 'Cancel');
            if ($delete == 'Yes') {
                $this->processorManager->removeProcessor($processor);
            }
            // Redirect to the Processors.
            return $this->redirect()->toRoute('processor');
        }

        // Render the view template.
        return new ViewModel([
            'processor' => $processor
        ]);
    }

}
