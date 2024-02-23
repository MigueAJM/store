<?php

namespace App\Exception;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class ExpiredSessionException extends AbstractException
{
	public function __construct(EntityManagerInterface $entityManager)
	{
		$code = Response::HTTP_UNAUTHORIZED;
		$message = Response::$statusTexts[$code]."|Expired session.";
		parent::__construct($entityManager, $message, $code, ErrorCode::EXPIRED_SESSION);
	}
}
