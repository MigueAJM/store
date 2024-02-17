<?php

namespace App\Entity;

use App\Interface\Entity;

final class UserCredential
{
	private ?string $email = null;
	private ?string $password = null;
	private bool $active = true;
	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): static
	{
		$this->email = trim($email);
		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): static
	{
		$this->password = trim($password);
		return $this;
	}

	public function getActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): static
	{
		$this->active = $active;
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
