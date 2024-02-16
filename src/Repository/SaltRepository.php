<?php

namespace App\Repository;

use App\Entity\Salt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salt>
 *
 * @method Salt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salt[]    findAll()
 * @method Salt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaltRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salt::class);
    }

    public function create(Salt $entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    public function updae(Salt $entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    public function delete(Salt $entity)
    {
        $this->remove($entity);
        $this->flush();
    }
}
