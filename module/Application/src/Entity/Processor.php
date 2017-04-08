<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Processors")
 */
class Processor {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * Return the ID of processor.
     * 
     * @return integer The ID of the processor.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the ID of the processor (auto generated if new).
     * 
     * @param integer $id The ID of the processor.
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Return the name of processor.
     * 
     * @return string The name of the processor.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the name of the processor (auto generated if new).
     * 
     * @param string $name The name of the processor.
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Return the description of processor.
     * 
     * @return string The description of the processor.
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the description of the processor (auto generated if new).
     * 
     * @param string $description The description of the processor.
     */
    public function setDescription($description) {
        $this->description = $description;
    }

}
