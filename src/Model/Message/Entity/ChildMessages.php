<?php

namespace App\Model\Message\Entity;

use App\Model\Message\Repository\ChildMessagesRepository;
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
     * ChildMessages constructor.
     * @param Message|null $message
     * @param Message|null $parentMessage
     */
    public function __construct(?Message $message, ?Message $parentMessage)
    {
        $this->message = $message;
        $this->parentMessage = $parentMessage;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setParentMessage(?Message $parentMessage): self
    {
        $this->parentMessage = $parentMessage;

        return $this;
    }
}
