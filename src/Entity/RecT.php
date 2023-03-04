<?php

namespace App\Entity;
use App\Controller\EntityManagerInterface;

use App\Repository\RecTRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecTRepository::class)]
class RecT
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('alpha',message:"Type name must contain letters only")]
    #[Assert\NotBlank(message:"Type name cannot be empty")]
    #[Assert\Length(min: 3)]
    private ?string $nomT = null;

    #[ORM\OneToMany(mappedBy: 'recT', targetEntity: Rec::class, cascade: ["remove"])]
    private Collection $Rec;

    public function __construct()
    {
        $this->Rec = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomT(): ?string
    {
        return $this->nomT;
    }

    public function setNomT(string $nomT): self
    {
        $this->nomT = $nomT;

        return $this;
    }

    /**
     * @return Collection<int, Rec>
     */
    public function getRec(): Collection
    {
        return $this->Rec;
    }

    public function addRec(Rec $rec): self
    {
        if (!$this->Rec->contains($rec)) {
            $this->Rec->add($rec);
            $rec->setRecT($this);
        }

        return $this;
    }

    public function removeRec(Rec $rec): self
    {
        if ($this->Rec->removeElement($rec)) {
            // set the owning side to null (unless already changed)
            if ($rec->getRecT() === $this) {
                $rec->setRecT(null);
            }
        }
        return $this;
    }
}
