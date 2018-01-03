<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Passage_Conditions")
 */
class PassageCondition
{
    /**
     * @ORM\Column(type="integer", name="id_condition")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_condition;

    /**
     * @ORM\Column(type="string", length=30, name="name")
     */
    private $name;

    public function setAccessName(string $name)
    {
        $this->name = $name;
    }

    public function getAccessName()
    {
        return $this->name;
    }

    public function getIdAccess()
    {
        return $this->id_condition;
    }
}