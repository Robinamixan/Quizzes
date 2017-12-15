<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Conditions")
 */
class Condition
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
    private $condition_name;

    public function setAccessName(string $name)
    {
        $this->condition_name = $name;
    }

    public function getAccessName()
    {
        return $this->condition_name;
    }

    public function getIdAccess()
    {
        return $this->id_condition;
    }
}