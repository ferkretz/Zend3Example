<?php

namespace ApplicationTest\Controller;

use Application\Controller\ComputerController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Application\Entity\Computer;

class ComputerControllerTest extends AbstractHttpControllerTestCase {

    public function setUp() {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
                        include __DIR__ . '/../../../../config/application.config.php', $configOverrides
        ));

        parent::setUp();
    }

    public function testComputerActionCanBeAccessed() {
        $this->dispatch('/computer'); // computers
        $this->assertResponseStatusCode(200); // no redirection
        $this->assertModuleName('application');
        $this->assertControllerName(ComputerController::class);
        $this->assertControllerClass('ComputerController');
        $this->assertMatchedRouteName('computer');
    }

    public function testCancelPost() {
        $postData = [
            'add' => 'Cancel',
        ];
        $this->dispatch('/computer/add', 'POST', $postData);
        $this->assertResponseStatusCode(302); // cancel, redirect to computers
        $this->assertRedirectTo('/computer');
    }

    public function testValidPost() {
        $postData = [
            'name' => 'Computer',
            'description' => 'Computer description',
            'dimm' => 'DDR SDRAM',
        ];
        $this->dispatch('/computer/add', 'POST', $postData);
        $this->assertResponseStatusCode(302); // success, redirect to computers
        $this->assertRedirectTo('/computer');
    }

    public function testInvalidPost() {
        $postData = [
            'name' => '', // invalid
            'description' => 'Computer description',
            'dimm' => 'DDR SDRAM',
            'add' => 'Ok'
        ];
        $this->dispatch('/computer/add', 'POST', $postData);
        $this->assertResponseStatusCode(200); // no redirection
    }

    public function testDatabase() {
        // You must have valid database to pass this!
        // fetch a computer
        $entityManager = $this->getApplicationServiceLocator()->get('doctrine.entitymanager.orm_default');
        $computer = $entityManager->getRepository(Computer::class)->findOneById(1);
        $this->assertEquals((int) $computer->getId(), 2); // compare intentifiers
    }

}
