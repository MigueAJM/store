<?php

namespace App\Exception;

use App\Entity\Error as EntityError;
use App\Repository\ErrorRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AbstractException extends Exception
{
	private ?int $errorCode = null;
	private RequestStack $requestStack;

	public function __construct(EntityManagerInterface $entityManager, string $message, int $httpCode, int $errorCode)
	{
		parent::__construct($message, $httpCode);
		$this->errorCode = $errorCode;
		$this->saveError($entityManager->getRepository(EntityError::class));
		$this->requestStack = new RequestStack();
	}

	public function getErrorCode()
	{
		return $this->errorCode;
	}

	private function saveError(ErrorRepository $errorRepository): void
	{
		$request = $this->getRequest();
		$error = new EntityError();
		$error->setUsername($request->headers->get("user") ?? "guest");
		$error->setPlatform($request->headers->get("User-Agent") ?? "Unknown");
		$error->setCreatedAt(new DateTimeImmutable());
		$error->setType(static::class);
		$error->setError($this->getMessage());
		$error->setHttpCode($this->getCode());
		$error->setErrorCode($this->errorCode);
		$error->setBody(["body" => $request->getContent(), "method" => $request->getMethod()]);
		$errorRepository->save($error);
	}

	private function getRequest(): ?Request
	{
		return $this->requestStack->getCurrentRequest();
	}
}
