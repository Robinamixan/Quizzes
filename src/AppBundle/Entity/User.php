<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

/**
 * User
 *
 * @ORM\Table("Accounts")
 * @ORM\Entity
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=60, name="full_name", nullable=true)
     */
    private $full_name;

    /**
     * @ORM\ManyToOne(targetEntity="UserCondition")
     * @ORM\JoinColumn(name="id_condition", referencedColumnName="id_condition")
     */
    private $condition;

    public function setFullName(string $name)
    {
        $this->full_name = $name;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function setCondition(UserCondition $condition)
    {
        $this->condition = $condition;
    }

    public function getCondition()
    {
        return array($this->condition->getName());
    }
}