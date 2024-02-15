<?php

namespace App\Repository;

use App\Entity\Salt;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Salt>
 *
 * @method Salt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salt[]    findAll()
 * @method Salt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaltRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salt::class);
    }
}
