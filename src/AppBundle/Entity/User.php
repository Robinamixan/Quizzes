<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Accounts", indexes={
 *                                          @ORM\Index(name="User_Email_uindex", columns={"email"}),
 *                                          @ORM\Index(name="User_Login_uindex", columns={"username"})
 *                                          })
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer", name="id_user")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_user;

    /**
     * @ORM\Column(type="string", length=30, name="username")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100, name="password")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=40, name="email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60, name="full_name", nullable=true)
     */
    private $full_name;

    /**
     * @ORM\ManyToOne(targetEntity="Access")
     * @ORM\JoinColumn(name="id_access", referencedColumnName="id_access")
     */
    private $access;

    public function setUsername(string $login):void
    {
        $this->username = $login;
    }

    public function setPassword(string $password):void
    {
        $this->password = $password;
    }

    public function setEmail(string $email):void
    {
        $this->email = $email;
    }

    public function setFullName(string $name):void
    {
        $this->full_name = $name;
    }

    public function setAccess(Access $access):void
    {
        $this->access = $access;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function getRoles()
    {
        return array($this->access->getAccessName());
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id_user,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id_user,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}