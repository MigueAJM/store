<?php

namespace App\Repository;

use App\Entity\Rol;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rol>
 *
 * @method Rol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rol[]    findAll()
 * @method Rol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }
}
