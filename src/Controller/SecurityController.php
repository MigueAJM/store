<?php

namespace App\Controller;

use App\Entity\Salt;
use App\Entity\Session;
use App\Entity\User;
use App\Entity\UserCredential;
use App\Exception\NotFoundEmailException;
use App\Exception\NotFoundPasswordException;
use App\Exception\NotFoundSaltException;
use App\Repository\SaltRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'security')]
class SecurityController extends Controller
{
    /**
     * @throws NotFoundEmailException
     * @throws NotFoundPasswordException
     */
    #[Route('/sign-in', name: '_sign_in', methods: ['POST'])]
    public function signIn(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userCredential = UserCredential::fromArray(json_decode($request->getContent(), true));
        $userRepository = $entityManager->getRepository(User::class);
        $searchUser = $userRepository->findOneBy(['email' => $userCredential->getEmail()]);
        if(!$searchUser) throw new NotFoundEmailException($entityManager);
        $encrytPassword = $this->encryptPassword($entityManager, $userCredential->getPassword(), $searchUser);
        $userCredential->setPassword($encrytPassword);
        $searchUser = $userRepository->findOneBy($userCredential->toArray());
        if(!$searchUser) throw new NotFoundPasswordException($entityManager);
        $sessionRepository = $entityManager->getRepository(Session::class);
        $session = $this->createSession($sessionRepository, $searchUser);
        $jwt = $this->generateJWT(['key' => $session->getUuid()]);
        return $this->json(['authorization' => $jwt]);
    }

    #[Route('/sign-out', name: 'sign_out', methods: ['GET'])]
    public function signOut(Request $request, SessionRepository $sessionRepository): Response
    {
        $jwt = $request->query->get('authorization');
        if(!$jwt){
            $jwt = $this->getAuthorizationToken($request);
        }
        $payload = $this->decodeJWT($jwt);
        $uuidSession = $payload->data->key ?? null;
        if(!$uuidSession) throw new UnauthorizedHttpException('', "Unauthorized (invalid authorization token.)");
        $searchSession = $sessionRepository->findOneBy(['uuid' => $uuidSession]);
        if(!$searchSession) throw new UnauthorizedHttpException('', "Unauthorized (invalid authorization token.)");
        $searchSession->setActive(false);
        $sessionRepository->update($searchSession);
        return $this->json([]);
    }

    /**
     * @throws NotFoundSaltException
     */
    static function encryptPassword(
        EntityManagerInterface $entityManager,
        string $password,
        ?User $user = null
    ): string
    {
        $salt = bin2hex(random_bytes(4));
        if($user){
            $saltRepository = $entityManager->getRepository(Salt::class);
            $criteria = ['useri' => $user->getId()];
            $searchSalt = $saltRepository->findOneBy($criteria);
            if(!$searchSalt) throw new NotFoundSaltException($entityManager);
            $salt = $searchSalt->getSalt();
        }
        $fullPassword = $salt . $password;
        return hash($_ENV['ALG'], $fullPassword);
    }

    private function createSession(SessionRepository $sessionRepository, User $user): Session
    {
        $uuid = Uuid::uuid4()->toString();
        $expired = time() + (4 * 3600);
        $session = new Session();
        $session->setUseri($user->getId());
        $session->setUuid($uuid);
        $session->setActive(true);
        $session->setExpired($expired);
        $sessionRepository->create($session);
        return $session;
    }
}
