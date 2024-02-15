<?php

namespace App\Repository;

use App\Interface\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<static>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractRepository extends ServiceEntityRepository implements Repository
{
	public function __construct(ManagerRegistry $registry, string $entityClass)
	{
		parent::__construct($registry, $entityClass);
	}

	public function create(object $entity): void
	{
		$this->persist($entity);
		$this->flush($entity);
	}

	public function update(object $entity): void
	{
		$this->persist($entity);
		$this->flush($entity);
	}

	public function delete(object $entity): void
	{
		$this->remove($entity);
		$this->flush();
	}
}