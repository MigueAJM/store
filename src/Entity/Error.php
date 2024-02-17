<?php

namespace App\Entity;

use App\Repository\ErrorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ErrorRepository::class)]
class Error
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $platform = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $error = null;

    #[ORM\Column]
    private ?int $httpCode = null;

    #[ORM\Column]
    private ?int $errorCode = null;

    #[ORM\Column]
    private array $body = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): static
    {
        $this->platform = $platform;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(string $error): static
    {
        $this->error = $error;
        return $this;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $code): static
    {
        $this->httpCode = $code;
        return $this;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $code): static
    {
        $this->errorCode = $code;
        return $this;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function setBody(array $body): static
    {
        $this->body = $body;
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
