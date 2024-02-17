<?php

namespace App\Exception;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class NotFoundPasswordException extends AbstractException
{
	public function __construct(EntityManagerInterface $entityManager)
	{
		$code = Response::HTTP_NOT_FOUND;
		$message = Response::$statusTexts[$code]."(Password)";
		parent::__construct($entityManager, $message, $code, ErrorCode::NOT_FOUND_PASSWORD);
	}
}
