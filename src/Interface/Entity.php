<?php
namespace App\Interface;

interface Entity
{
	public function toArray(): array;

	static function fromArray(array $entity): static;
}