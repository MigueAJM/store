<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Controller extends AbstractController
{
    public function generateJWT(mixed $payload): string
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

		public function decodeJWT(string $jwt): object
		{
			return JWT::decode($jwt, new Key($_ENV['JWT_KEY'] ?? '', $_ENV['JWT_ALG'] ?? ''));
		}

		/**
		 * @throws UnauthorizedHttpException undefined **authorization token**
		 */
		public function getAuthorizationToken(Request $request): string
		{
			$authorization = $request->headers->get('Authorization');
			if(!$authorization) throw new UnauthorizedHttpException('', "Undefined authorization token.", null, Response::HTTP_UNAUTHORIZED);
			return substr($authorization, 5);
		}
}
