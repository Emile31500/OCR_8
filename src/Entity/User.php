<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $role;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername() :?string
    {
        return $this->username;
    }

    public function setUsername(string $username) :?User
    {
        $this->username = $username;
        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword() :?string
    {
        return $this->password;
    }

    public function setPassword(string $password) :?User
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail() :?string
    {
        return $this->email;
    }

    public function setEmail(string $email) :?User
    {
        $this->email = $email;
        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return $this->role;
    }

    public function setRoles(array $role) :?User
    {
        $this->role = $role;
        return $this;
    }
}
