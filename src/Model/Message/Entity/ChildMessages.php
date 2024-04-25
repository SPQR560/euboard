<?php

namespace App\Model\Message\Entity;

use App\Model\Message\Repository\ChildMessagesRepository;
use App\Model\Thread\Entity\Thread;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChildMessagesRepository::class)
 */
class ChildMessages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $parentMessage;

    /**
     * @ORM\ManyToOne(targetEntity=Thread::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thread;

    /**
     * ChildMessages constructor.
     */
    public function __construct(?Message $message, ?Message $parentMessage, Thread $thread)
    {
        $this->message = $message;
        $this->parentMessage = $parentMessage;
        $this->thread = $thread;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setParentMessage(?Message $parentMessage): self
    {
        $this->parentMessage = $parentMessage;

        return $this;
    }

    public function getParentMessage(): ?Message
    {
        return $this->parentMessage;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }
}
