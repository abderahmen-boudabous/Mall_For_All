<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("suppliers")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("suppliers")]
    #[Assert\NotBlank (message:"name can not be empty")]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'fournisseurs')]
    #[Groups("suppliers")]
    #[Assert\NotBlank (message:"category can not be empty")]
    private ?CategorieF $categorie = null;



    #[ORM\Column]
    #[Groups("suppliers")]
    #[Assert\NotBlank (message:"tel can not be empty")]
    #[Assert\Length (min:8,minMessage:"phone number must contain 8 numbers")]
    #[Assert\Length (max:8,maxMessage:"phone number must contain 8 numbers")]
    #[Assert\Positive (message:"phone number must be positive")]
    private ?int $tel = null;

    #[ORM\Column(length: 255)]
    #[Groups("suppliers")]
    #[Assert\NotBlank (message:"address can not be empty")]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups("suppliers")]
    #[Assert\NotBlank (message:"email can not be empty")]
    #[Assert\Email (message:"not the right format of an email")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups("suppliers")]
    #[Assert\NotBlank (message:"website can not be empty")]
    #[Assert\Url (message:"not the right format of a website URL")]
    private ?string $website = null;

    #[ORM\Column(length: 255)]
    #[Groups("suppliers")]
    private ?string $img = null;

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

    public function getCategorie(): ?CategorieF
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieF $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }
}
