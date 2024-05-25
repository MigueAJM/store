<?php

namespace App\Controller;

use App\Entity\Salt;
use App\Entity\Session;
use App\Entity\User;
use App\Entity\UserCredential;
use App\Exception\NotFoundEmailException;
use App\Exception\NotFoundPasswordException;
use App\Exception\NotFoundSaltException;
use App\Repository\SessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'security')]
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
        $criteria = $userCredential->toArray();
        unset($criteria['password']);
        $searchUser = $userRepository->findOneBy($criteria);
        if(!$searchUser) throw new NotFoundEmailException($entityManager);
        $encrytPassword = $this->encryptPassword($entityManager, $userCredential->getPassword(), $searchUser);
        $userCredential->setPassword($encrytPassword);
        $criteria = $userCredential->toArray();
        $searchUser = $userRepository->findOneBy($criteria);
        if(!$searchUser) throw new NotFoundPasswordException($entityManager);
        $session = $this->createSession($entityManager, $searchUser);
        $payload = $searchUser->toArray();
        $payload["key"] =$session->getUuid();
        $jwt = $this->generateJWT($payload);
        return $this->json(['authorization' => $jwt]);
    }

    #[Route('/sign-out', name: 'sign_out', methods: ['GET', 'HEAD'])]
    public function signOut(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jwt = null;
        if($request->getMethod() === Request::METHOD_GET)
            $jwt = $request->query->get('authorization');
        if($request->getMethod() === Request::METHOD_HEAD)
            $jwt = $this->getAuthorizationToken($request);
        if(!$jwt) throw new UnauthorizedHttpException('', "Undefined authorization token.", null, Response::HTTP_UNAUTHORIZED);
        $payload = $this->decodeJWT($jwt);
        $uuidSession = $payload->data->key ?? null;
        if(!$uuidSession) throw new UnauthorizedHttpException('', "Unauthorized (invalid authorization token.)");
        $searchSession = $entityManager->getRepository(Session::class)->findOneBy(['uuid' => $uuidSession]);
        if(!$searchSession) throw new UnauthorizedHttpException('', "Unauthorized (invalid authorization token.)");
        $searchSession->setActive(false);
        $entityManager->persist($searchSession);
        $entityManager->flush();
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
        $salt = "";
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

    private function createSession(EntityManagerInterface $entityManager, User $user): Session
    {
        $uuid = $this->getUniqueUuid($entityManager->getRepository(Session::class));
        $expired = time() + (4 * 3600);
        $session = new Session();
        $session->setUseri($user);
        $session->setUuid($uuid);
        $session->setActive(true);
        $session->setExpired($expired);
        $session->setCreatedAt(new DateTimeImmutable());
        $session->setUpdatedAt(new DateTimeImmutable());
        $entityManager->persist($session);
        $entityManager->flush();
        return $session;
    }

    private function getUniqueUuid(SessionRepository $sessionRepository): string
    {
        $uuid = Uuid::uuid4()->toString();
        $searchSession = $sessionRepository->findOneBy(compact("unique"));
        if(!$searchSession) return $uuid;
        return $this->getUniqueUuid($sessionRepository);
    }

    static function genereteSalt(): string
    {
        return bin2hex(random_bytes(4));
    }
}
