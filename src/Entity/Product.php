<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Name cannot be empty")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Description cannot be empty")]
    #[Assert\Length(min: 1)]
    #[Assert\Type('float',message:"Price must contain numbers only")]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Type cannot be empty")]
    #[Assert\Type('alpha',message:"type must contain letters only")]
    private ?string $type = null;
    

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Stock cannot be empty")]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Shop $shop = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getShop(): ?shop
    {
        return $this->shop;
    }

    public function setShop(?shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }
}
