<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

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

    public function toArray(): array
	{
		$entity = get_object_vars($this);
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
