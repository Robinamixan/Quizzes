<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users_Account", indexes={
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
     * @ORM\Column(type="integer", name="id_access")
     *
     */
    private $id_access;

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

    public function setAccess(int $access):void
    {
        $this->id_access = $access;
    }

    public function getUsername():string
    {
        return $this->username;
    }

    public function getPassword():string
    {
        return $this->password;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function getFullName():string
    {
        return $this->full_name;
    }

    public function getId_user():string
    {
        return $this->id_user;
    }

    public function getIdAccess():string
    {
        return $this->id_access;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
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