<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
				$code = $exception->getCode() <= 99 ? 500 : $exception->getCode();
				$payload = [
					'error' => [
						'exception' => end(explode("\\", get_class($exception))),
						'httpCode' => $code,
						'message' => $exception->getMessage()
					]
				];
				if(method_exists($exception, "getErrorCode")){
					$payload['error']['code'] = $exception->getErrorCode();
				}
				$response = new JsonResponse($payload, $code);
        $event->setResponse($response);
    }
}