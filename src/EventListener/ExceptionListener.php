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
				$explodeClass = explode("\\", get_class($exception));
				$className = isset($explodeClass[1]) ? end($explodeClass) : $explodeClass[0];
				$payload = [
					'error' => [
						'exception' => $className,
						'httpCode' => $code,
						'message' => $exception->getMessage()
					]
				];
				if(method_exists($exception, "getErrorCode")){
					$payload['error']['code'] = $exception->getErrorCode();
				}
				if($code === 500 && $_ENV['APP_ENV'] != "dev"){
					$payload['error']['message'] = "Internal server error.";
				}
				$response = new JsonResponse($payload, $code);
        $event->setResponse($response);
    }
}