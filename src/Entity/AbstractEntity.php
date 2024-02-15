<?php
namespace App\Entity;

use App\Interface\Entity;

class AbstractEntity implements Entity
{
	private ?array $privateProperty = null;
	public function __construct(array $privateProperty = [])
	{
		$this->privateProperty = $privateProperty;
	}

	public function toArray(): array
	{
		$entity = get_object_vars($this);
		foreach ($this->privateProperty as $k) {
			unset($entity[$k]);
		}
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