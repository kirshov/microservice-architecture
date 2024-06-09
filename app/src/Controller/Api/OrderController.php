<?php

namespace App\Controller\Api;

use App\Helpers\AuthHelper;
use App\Model\Order\UseCase\Create\Command;
use App\Model\Order\UseCase\Create\Handler;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api/order', name: 'api_order_')]
class OrderController
{
	#[Route('/create', name: 'create', methods: ['POST'])]
	public function create(Request $request, Handler $handler): Response
	{
		try {
			$userId = AuthHelper::getUserIdFromRequest($request);

			if (empty($userId)) {
				throw new Exception('Доступ запрещен');
			}

			$requestArray = json_decode($request->getContent(), true);

			$command = new Command();
			$command->userId = $userId;
			$command->name = $requestArray['name'];
			$command->cost = $requestArray['cost'];

			$handler->handle($command);

			$response = [
				'status' => 'success',
			];
		} catch (Throwable $e) {
			$response = [
				'error' => $e->getMessage(),
			];
		}

		return new JsonResponse($response);
	}
}
