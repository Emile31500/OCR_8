<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    public function getId() :?int
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) :?Task
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getTitle() :?string
    {
        return $this->title;
    }

    public function setTitle(string $title) :?Task
    {
        $this->title = $title;
        return $this;
    }

    public function getContent() :?string
    {
        return $this->content;
    }

    public function setContent(string $content) :?Task
    {
        $this->content = $content;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user) :?Task
    {
        $this->user = $user;
        return $this;
    }

    public function isDone() :?bool
    {
        return $this->isDone;
    }

    public function toggle($flag) :?Task
    {
        $this->isDone = $flag;
        return $this;
    }
}
