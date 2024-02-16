<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
				$payload = [
					'error' => [
						'exception' => get_class($exception),
						'httpCode' => $exception->getCode(),
						'message' => $exception->getMessage()
					]
				];
				if(method_exists($exception, "getErrorCode")){
					$payload['error']['code'] = $exception->getErrorCode();
				}
				$response = new JsonResponse($payload, $exception->getCode());
        $event->setResponse($response);
    }
}