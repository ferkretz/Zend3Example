<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Memory;
use Application\Entity\Processor;

/**
 * @ORM\Entity
 * @ORM\Table(name="Computers")
 */
class Computer {

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
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Memory", inversedBy="Computers")
     * @ORM\JoinTable(name="ComputerMemoryRelations",
     *      joinColumns={@ORM\JoinColumn(name="computerId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="memoryId", referencedColumnName="id")}
     *      )
     */
    protected $memories;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Processor")
     * @ORM\JoinColumn(name="processorId", referencedColumnName="id")
     */
    protected $processor;

    public function __construct() {
        $this->memories = new ArrayCollection();
    }

    /**
     * Return the ID of computer.
     *
     * @return integer The ID of the computer.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the ID of the computer (auto generated if new).
     *
     * @param integer $id The ID of the computer.
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Return the name of computer.
     *
     * @return string The name of the computer.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the name of the computer (auto generated if new).
     *
     * @param string $name The name of the computer.
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
     * Return the description of computer.
     *
     * @return string The description of the computer.
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the description of the computer (auto generated if new).
     *
     * @param string $description The description of the computer.
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Returns memories of the Computer.
     * @return array
     */
    public function getMemories() {
        return $this->memories;
    }

    /**
     * Returns memory ids of the Computer.
     * @return array
     */
    public function getMemoryIds() {
        $memoryIds = array();

        foreach ($this->memories as $memory) :
            $memoryIds[] = $memory->getId();
        endforeach;

        return $memoryIds;
    }

    /**
     * Adds a new memory to the Computer.
     * @param $memory
     */
    public function addMemory($memory) {
        $this->memories[] = $memory;
    }

    /**
     * Deletes a memory from the Computer.
     * @param $id
     * @return TRUE whether to update needed and FALSE otherwise
     */
    public function deleteMemory($id) {
        foreach ($this->memories as $memory) :
            if ($memory->getId() == $id) {
                $this->memories->removeElement($memory);
                return TRUE;
            }
        endforeach;
        return FALSE;
    }

    /**
     * Deletes all memories from the Computer.
     */
    public function deleteMemories() {
        $this->memories->clear();
    }

    /**
     * Returns processor of the Computer.
     * @return Processor The processor.
     */
    public function getProcessor() {
        return $this->processor;
    }

    /**
     * Sets the processor to the Computer.
     * @param $processor
     */
    public function setProcessor($processor) {
        $this->processor = $processor;
    }

    /**
     * Deletes the processor from the Computer.
     */
    public function deleteProcessor() {
        $this->processor = NULL;
    }

}
