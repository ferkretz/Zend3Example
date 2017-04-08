<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Memories")
 */
class Memory {

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
     * @ORM\Column(name="dimm")
     */
    protected $dimm;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * Return the ID of memory.
     * 
     * @return integer The ID of the memory.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the ID of the memory (auto generated if new).
     * 
     * @param integer $id The ID of the memory.
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Return the name of memory.
     * 
     * @return string The name of the memory.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the name of the memory.
     * 
     * @param string $name The name of the memory.
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Return the DIMM of memory.
     * 
     * @return string The DIMM of the memory.
     */
    public function getDIMM() {
        return $this->dimm;
    }

    /**
     * Sets the DIMM of the memory.
     * 
     * @param string $dimm The name of the memory.
     */
    public function setDIMM($dimm) {
        $this->dimm = $dimm;
    }

    /**
     * Return the description of memory.
     * 
     * @return string The description of the memory.
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the description of the memory (auto generated if new).
     * 
     * @param string $description The description of the memory.
     */
    public function setDescription($description) {
        $this->description = $description;
    }

}
