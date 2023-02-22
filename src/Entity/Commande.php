<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Name is required")]
    #[Assert\Length (min:5,minMessage:"Name too short")]
    #[Assert\Length (max:16,maxMessage:"Name too long")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Adress is required")]
    #[Assert\Length (min:5,minMessage:"Adress too short")]
    #[Assert\Length (max:16,maxMessage:"Adress too long")]

    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank (message:"Quantity is required")]
    #[Assert\Positive (message:"Quantity must be positive")]
    #[Assert\Length (max:2,maxMessage:"Too much quantity")]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column]
    #[Assert\NotBlank (message:"Phone number is required")]
    #[Assert\Positive (message:"Phone must should be positive")]
    #[Assert\Length (min:8,minMessage:"Unavailable phone number ")]
    #[Assert\Length (max:8,maxMessage:"Unavailable phone number")]
    private ?int $telephone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit  $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
    
    
}
