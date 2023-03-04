<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("products")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    #[Assert\NotBlank(message:"Name cannot be empty")]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups("products")]
    #[Assert\NotBlank(message:"Description cannot be empty")]
    #[Assert\Length(min: 1)]
    #[Assert\Type('float',message:"Price must contain numbers only")]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    #[Assert\NotBlank(message:"Type cannot be empty")]
    #[Assert\Type('alpha',message:"type must contain letters only")]
    private ?string $type = null;
    

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $photo = null;

    #[ORM\Column]
    #[Groups("products")]
    #[Assert\NotBlank(message:"Stock cannot be empty")]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Shop $shop = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("products")]
    private ?string $photo2 = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Comment::class)]
    private Collection $comments;

    
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        
    }

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

    public function getPhoto2(): ?string
    {
        return $this->photo2;
    }

    public function setPhoto2(?string $photo2): self
    {
        $this->photo2 = $photo2;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }


   
}
