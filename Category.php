<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validation;




#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('alpha',message:"Titre must contain letters only")]
    #[Assert\NotBlank(message:"titre cannot be empty")]
    #[Assert\Length(min: 3)]
    #[Assert\Length(max: 10)]
    private ?string $titre = null;


    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type(type: 'string', message: 'Description must be a string')]
    #[Assert\NotBlank(message: 'Description cannot be empty')]
    #[Assert\Length(max: 255)]
    #[Regex(pattern: '/^[a-zA-Z0-9\sé,.]*$/', message: 'Description must contain letters, numbers, spaces, \'é\', \',\' or \'.\' only')]
    private ?string $description = null;

    

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Event::class , cascade: ["remove"])]
    private Collection $Event;

    public function __construct()
    {
        $this->Event = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvent(): Collection
    {
        return $this->Event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->Event->contains($event)) {
            $this->Event->add($event);
            $event->setCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->Event->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCategory() === $this) {
                $event->setCategory(null);
            }
        }

        return $this;
    }
}
