<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Accesses", indexes={@ORM\Index(name="Accesses_Name_uindex", columns={"Name"})})
 */
class Access
{
    /**
     * @ORM\Column(type="integer", name="id_access")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_access;

    /**
     * @ORM\Column(type="string", length=30, name="name")
     */
    private $name;

    public function setAccessName(string $access_name)
    {
        $this->name = $access_name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIdAccess()
    {
        return $this->id_access;
    }
}