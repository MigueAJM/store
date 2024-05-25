<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $detail = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $discount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $iva = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $subtotal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $total = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetail(): array
    {
        return $this->detail;
    }

    public function setDetail(array $detail): static
    {
        $this->detail = $detail;
        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(string $discount): static
    {
        $this->discount = $discount;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function getIva(): ?string
    {
        return $this->iva;
    }

    public function setIva(string $iva): static
    {
        $this->iva = $iva;
        return $this;
    }

    public function getSubtotal(): ?string
    {
        return $this->subtotal;
    }

    public function setSubtotal(string $subtotal): static
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;
        return $this;
    }

    public function toArray(): array
	{
		$entity = get_object_vars($this);
        $entity['client'] = $this->client->toArray();
		return $entity;
	}

	public static function fromArray(array $entity): static
	{
		$newEntity = new static();
		foreach ($entity as $k => $v) {
			$method = "set".ucfirst($k);
			if(method_exists($newEntity::class, $method)){
				$newEntity->$method($v);
			}
		}
		return $newEntity;
	}
}
