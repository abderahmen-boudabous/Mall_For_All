<?php

namespace App\Entity;

use App\Repository\RecRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: RecRepository::class)]
class Rec
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Subject cannot be empty")]
    #[Assert\Length(min: 3)]
    private ?string $sujet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Content cannot be empty")]
    #[Assert\Length(min: 3)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Username cannot be empty")]
    #[Assert\Type('alpha',message:"Username must contain letters only")]
    #[Assert\Length(min: 2)]
    #[Assert\Type('string')]
    private ?string $userR = null;

    #[ORM\ManyToOne(inversedBy: 'Rec')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecT $recT = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = 'Not solved';

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Email cannot be empty")]
    #[Assert\Email(message:"Please enter a valid email")]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'recm', targetEntity: Message::class, cascade: ["remove"])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getUserR(): ?string
    {
        return $this->userR;
    }

    public function setUserR(string $userR): self
    {
        $this->userR = $userR;

        return $this;
    }

    public function getRecT(): ?RecT
    {
        return $this->recT;
    }

    public function setRecT(?RecT $recT): self
    {
        $this->recT = $recT;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setRecm($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getRecm() === $this) {
                $message->setRecm(null);
            }
        }

        return $this;
    }
    
}
