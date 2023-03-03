<?php

namespace App\Entity;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenum = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Rec $recm = null;

    #[ORM\Column(length: 255)]
    private ?string $sender = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenum(): ?string
    {
        return $this->contenum;
    }

    public function setContenum(string $contenum): self
    {
        $this->contenum = $contenum;

        return $this;
    }

    public function getRecm(): ?Rec
    {
        return $this->recm;
    }

    public function setRecm(?Rec $recm): self
    {
        $this->recm = $recm;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }
}
