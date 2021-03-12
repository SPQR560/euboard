<?php
declare(strict_types=1);
namespace App\Model\Message\Entity;

use App\Model\Message\Repository\MessageRepository;
use App\Model\Thread\Entity\Thread;
use App\Model\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class)
     */
    private $childMessages;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class)
     */
    private $parentMessages;

    /**
     * @ORM\ManyToOne(targetEntity=Thread::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    public function __construct()
    {
        $this->childMessages = new ArrayCollection();
        $this->parentMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildMessages(): Collection
    {
        return $this->childMessages;
    }

    public function addChildMessage(self $childMessage): self
    {
        if (!$this->childMessages->contains($childMessage)) {
            $this->childMessages[] = $childMessage;
        }

        return $this;
    }

    public function removeChildMessage(self $childMessage): self
    {
        $this->childMessages->removeElement($childMessage);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParentMessages(): Collection
    {
        return $this->parentMessages;
    }

    public function addParentMessage(self $parentMessage): self
    {
        if (!$this->parentMessages->contains($parentMessage)) {
            $this->parentMessages[] = $parentMessage;
        }

        return $this;
    }

    public function removeParentMessage(self $parentMessage): self
    {
        $this->parentMessages->removeElement($parentMessage);

        return $this;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


}
