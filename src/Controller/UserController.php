<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Salt;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/user', name: 'api_user')]
class UserController extends Controller
{
    #[Route('/register', name: '_register', methods:['POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $user = User::fromArray(json_decode($request->getContent(), true));
        $errors = $validator->validate($user);
        if(count($errors)){
            return $this->json(['error' => $errors], 400);
        }
        if($user->getPassword() != $user->getConfirmPassword()){
            return $this->json(['error' => "Password do not match."], 400);
        }
        $salt = SecurityController::genereteSalt();
        $roleRepository = $entityManager->getRepository(Role::class);
        $user->setRole($roleRepository->findOneBy(['name' => "ROLE_USER"]));
        $entityManager->persist($user);
        $entityManager->flush();
        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['email' => $user->getEmail()]);
        $saltEntity = new Salt();
        $saltEntity->setSalt($salt);
        $saltEntity->setUseri($user->getId());
        $entityManager->persist($saltEntity);
        $entityManager->flush();
        $password = SecurityController::encryptPassword(
            $entityManager,
            $user->getPassword(),
            $user
        );
        $user->setPassword($password);
        $entityManager->flush();
        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/unactive/{id<\d+>}', name: '_unactive', methods: ['PUT'])]
    public function unactive(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setActive(false);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([]);
    }
}
