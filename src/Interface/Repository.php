<?php
namespace App\Interface;

interface Repository
{
	public function create(object $entity): void;

	public function update(object $entity): void;

	public function delete(object $entity): void;
}