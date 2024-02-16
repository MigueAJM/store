<?php

namespace App\Exception;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class NotFoundSaltException extends AbstractException
{
	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager, Response::$statusTexts[404], Response::HTTP_NOT_FOUND, ErrorCode::NOT_FOUND_SALT);
	}
}
