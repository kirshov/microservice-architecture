<?php

namespace App\Controller\Api;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\UserRepository;
use App\Model\User\UseCase\Create\Command;
use App\Model\User\UseCase\Create\Handler;
use App\Model\User\UseCase\Delete\Command as DeleteCommand;
use App\Model\User\UseCase\Delete\Handler as DeleteHandler;
use App\Model\User\UseCase\Update\Command as UpdateCommand;
use App\Model\User\UseCase\Update\Handler as UpdateHandler;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api/user', name: 'api_user_')]
class UserController
{
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, Handler $handler): Response
    {
        try {
            $requestArray = json_decode($request->getContent(), true);

            $command = new Command();
            $command->name = $requestArray['name'];
            $command->email = $requestArray['email'];

			$user = $handler->handle($command);

			$response = $this->getUserDataResponse($user);
        } catch (Throwable $e) {
            $response = [
                'error' => $e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Route('/update', name: 'update', methods: ['PUT'])]
    public function update(Request $request, UpdateHandler $handler): Response
    {
        try {
            $requestArray = json_decode($request->getContent(), true);

            $command = new UpdateCommand();
            $command->id = (int) $request->get('id');
			if (isset($requestArray['name'])) {
				$command->name = $requestArray['name'];
			}

			if (isset($requestArray['email'])) {
				$command->email = $requestArray['email'];
			}

            $user = $handler->handle($command);

			$response = $this->getUserDataResponse($user);
        } catch (Throwable $e) {
            $response = [
                'error' => $e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Route('/get', name: 'get', methods: ['GET'])]
    public function get(Request $request, UserRepository $userRepository): Response
    {
        try {
            $id = (int) $request->get('id');

            $user = $userRepository->findById($id);

            if (null === $user) {
                throw new RuntimeException('User not found');
            }

            $response = $this->getUserDataResponse($user);
        } catch (Throwable $e) {
            $response = [
                'error' => $e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

	#[Route('/delete', name: 'delete', methods: ['DELETE'])]
	public function delete(Request $request, DeleteHandler $handler): Response
	{
		try {
			$command = new DeleteCommand();
			$command->id = (int) $request->get('id');

			$handler->handle($command);

			$response = [
				'id' => $command->id,
			];
		} catch (Throwable $e) {
			$response = [
				'error' => $e->getMessage(),
			];
		}

		return new JsonResponse($response);
	}

	/**
	 * @param User $user
	 * @return array
	 */
	private function getUserDataResponse(User $user): array
	{
		return [
			'id' => $user->getId(),
			'email' => $user->getEmail(),
			'name' => $user->getName(),
			'status' => $user->getStatus(),
		];
	}
}
