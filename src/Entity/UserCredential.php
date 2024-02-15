<?php

namespace App\Entity;

final class UserCredential extends AbstractEntity
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
		$this->email = trim($password);
		return $this;
	}

	public function getActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): static
	{
		$this->active =true;
		return $this;
	}
}
