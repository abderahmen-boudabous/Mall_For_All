<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validation;




#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    #[Assert\NotBlank(message:"Nom cannot be empty")]
    #[Regex(pattern: '/^[a-zA-Z0-9\sé,.]*$/', message: ' text ')]
    #[Regex(pattern: '/^[a-zA-Z\sé,.]*$/', message: 'Nom can contain letters and spaces only')]

    #[Assert\Length(min: 5)]
    #[Assert\Length(max: 30)]
    private ?string $Nom = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotBlank(message:"Spot cannot be empty")]
    private ?int $Spot = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Duration cannot be empty")]
    #[Assert\Length(min: 4)]
    #[Assert\Length(max: 20)]
    private ?string $Duration = null;

    #[ORM\ManyToOne(inversedBy: 'Event')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan("today", message: "The date must be in the future")]
    private ?\DateTimeInterface $Date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getSpot(): ?int
    {
        return $this->Spot;
    }

    public function setSpot(int $Spot): self
    {
        $this->Spot = $Spot;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->Duration;
    }

    public function setDuration(string $Duration): self
    {
        $this->Duration = $Duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }
}
