<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use App\Exception\ExpiredSessionException;
use App\Exception\NotFoundSessionException;
use App\Interface\JsonWebTokenInterface;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Controller extends AbstractController
{
    static function generateJWT(mixed $payload): string
		{
			$currentTimestamp = time();
			$payload = [
				'iss' => $_ENV['APP_URL'],
				'iat' => $currentTimestamp,
				'nbf' => $currentTimestamp,
				'exp' => $currentTimestamp + (4 * 3600),
				'data' => $payload
			];
			return JWT::encode($payload, $_ENV['JWT_KEY'], $_ENV['JWT_ALG']);
		}

		static function decodeJWT(string $jwt): JsonWebTokenInterface
		{
			$payload = JWT::decode($jwt, new Key($_ENV['JWT_KEY'] ?? '', $_ENV['JWT_ALG'] ?? ''));
			return new JsonWebTokenInterface((array)$payload);
		}

		/**
		 * @throws UnauthorizedHttpException undefined **authorization token**
		 */
		static function getAuthorizationToken(Request $request): string
		{
			$authorization = $request->headers->get('Authorization');
			if(!$authorization) throw new UnauthorizedHttpException('', "Undefined authorization token.", null, Response::HTTP_UNAUTHORIZED);
			return substr($authorization, 5);
		}

		/**
		 * @throws NotFoundSessionException
		 */
		static public function getAuthenticatedUser(Request $request, EntityManagerInterface $entityManagerInterface): User
		{
			$jwt = self::getAuthorizationToken($request);
			$payload = self::decodeJWT($jwt);
			$uuidSession = $payload->data->key;
			$sessionRepository = $entityManagerInterface->getRepository(Session::class);
			$sessionActive = $sessionRepository->findOneBy(['uuid' => $uuidSession]);
			if(!$sessionActive) throw new NotFoundSessionException($entityManagerInterface);
			$now = time();
			if(!$sessionActive->getActive() || $now > $sessionActive->getExpired()) throw new ExpiredSessionException($entityManagerInterface);
			return $sessionActive->getUseri();
		}
}
