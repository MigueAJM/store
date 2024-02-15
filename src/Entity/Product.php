<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your name cannot be longer than {{ limit }} characters',
    )]
    private string $name;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $stock;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private Category $category;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private float $priceOld = 0.00;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private float $price = 0.00;

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    
    #[ORM\Column(length: 25)]
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = trim($name);
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = trim($description);
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategoryId(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getPriceOld(): float
    {
        return $this->priceOld;
    }

    public function setPriceOld(float $price): static
    {
        $this->priceOld = $price;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->image = $code;
        return $this;
    }
}
