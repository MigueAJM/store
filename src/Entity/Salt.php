<?php

namespace App\Entity;

use App\Repository\SaltRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaltRepository::class)]
class Salt extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
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
}
