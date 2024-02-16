<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserCredential;
use App\Repository\SaltRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'security')]
class SecurityController extends Controller
{
    #[Route('/sign-in', name: '_sign_in', methods: ['POST'])]
    public function signIn(Request $request, UserRepository $userRepository): Response
    {

        return $this->json([]);
    }

    static function encryptPassword(
        SaltRepository $saltRepository,
        string $password,
        ?User $user = null
    ): string
    {
        $salt = bin2hex(random_bytes(4));
        if($user){
            $criteria = ['useri' => $user->getId()];
            $searchSalt = $saltRepository->findOneBy($criteria);
            if(!$searchSalt);
        }
        return '';
    }
}
