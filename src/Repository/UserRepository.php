<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserCredential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function signIn(UserCredential $credentials): ?User
    {
        return $this->findOneBy($credentials->toArray());
    }

    public function signOut(SessionRepository $session, string $uuid): bool
    {
        $activeSession = $session->findOneBy(compact("uuid"));
        if(!$activeSession) return false;
        $activeSession->setActive(false);
        $this->flush($activeSession);
        return true;
    }

    public function create(User $entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    public function update(User $entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    public function delete(User $entity)
    {
        $this->remove($entity);
        $this->flush();
    }
}
