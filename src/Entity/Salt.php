<?php

namespace App\Entity;

use App\Repository\SaltRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaltRepository::class)]
class Salt 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $salt = null;

    #[ORM\Column]
    private ?int $useri = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): static
    {
        $this->salt = $salt;

        return $this;
    }

    public function getUseri(): ?int
    {
        return $this->useri;
    }

    public function setUseri(int $useri): static
    {
        $this->useri = $useri;

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
