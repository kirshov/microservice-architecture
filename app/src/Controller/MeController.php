<?php

namespace App\Controller;

use App\Helpers\AuthHelper;
use App\Model\User\Entity\UserRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class MeController
{
	#[Route('/me', name: 'me')]
	public function me(Request $request, UserRepository $userRepository): Response
	{
		try {
			$userId = AuthHelper::getUserIdFromRequest($request);

			$user = $userRepository->findById($userId);

			if (null === $user) {
				throw new RuntimeException('User not found');
			}

			$response = [
				'id' => $user->getId(),
				'email' => $user->getEmail(),
				'name' => $user->getName(),
				'status' => $user->getStatus(),
			];
		} catch (Throwable $e) {
			$response = [
				'error' => $e->getMessage(),
			];
		}

		return new JsonResponse($response);
	}
}
