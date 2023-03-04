<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Product $product = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Likes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Dislike = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getLikes(): ?string
    {
        return $this->Likes;
    }

    public function setLikes(?string $Likes): self
    {
        $this->Likes = $Likes;

        return $this;
    }

    public function getDislike(): ?string
    {
        return $this->Dislike;
    }

    public function setDislike(?string $Dislike): self
    {
        $this->Dislike = $Dislike;

        return $this;
    }

    
}
