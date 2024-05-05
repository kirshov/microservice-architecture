<?php

declare(strict_types=1);

namespace App\Event\Subscriber;

use App\Controller\Api\UserController;
use App\Helpers\AuthHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class CheckAccessSubscriber implements EventSubscriberInterface
{
	public function onKernelController(ControllerEvent $event): void
	{
		$controller = $event->getController();

		if (is_array($controller)) {
			$controller = $controller[0];
		}

		if ($controller instanceof UserController) {
			$userId = AuthHelper::getUserIdFromRequest($event->getRequest());

			if (empty($userId)) {
				//throw new AccessDeniedHttpException('Access denied');
				http_response_code(403);
				echo 'Access denied';
				exit();
			}
		}
	}

	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::CONTROLLER => 'onKernelController',
		];
	}
}