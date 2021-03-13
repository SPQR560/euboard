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
     * @ORM\Column(type="integer")
     */
    private $messageId;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ParentMessage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageId(): ?int
    {
        return $this->messageId;
    }

    public function setMessageId(int $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function getParentMessage(): ?Message
    {
        return $this->ParentMessage;
    }

    public function setParentMessage(?Message $ParentMessage): self
    {
        $this->ParentMessage = $ParentMessage;

        return $this;
    }
}
