<?php

namespace App\Exception;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class NotFoundEmailException extends AbstractException
{
	public function __construct(EntityManagerInterface $entityManager)
	{
		$code = Response::HTTP_NOT_FOUND;
		$message = Response::$statusTexts[$code]."(Email)";
		parent::__construct($entityManager, $message, $code, ErrorCode::NOT_FOUND_EMAIL);
	}
}
