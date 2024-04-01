<?php

namespace App\Controller;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\UserRepository;
use App\Model\User\UseCase\Create\Command;
use App\Model\User\UseCase\Create\Handler;
use App\Model\User\UseCase\Update\Command as UpdateCommand;
use App\Model\User\UseCase\Update\Handler as UpdateHandler;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/user', name: 'user_')]
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

            $userId = $handler->handle($command);

            $response = [
                'id' => $userId,
            ];
        } catch (Throwable $e) {
            $response = [
                'error' => $e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Route('/update', name: 'update', methods: ['POST'])]
    public function update(Request $request, UpdateHandler $handler): Response
    {
        try {
            $requestArray = json_decode($request->getContent(), true);

            $command = new UpdateCommand();
            $command->name = $requestArray['name'];
            $command->email = $request->get('email');

            $handler->handle($command);

            $response = [
                'success' => true,
            ];
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
            $email = new Email($request->get('email'));

            $user = $userRepository->findByEmail($email);

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
