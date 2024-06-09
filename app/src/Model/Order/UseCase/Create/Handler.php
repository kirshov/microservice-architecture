<?php

namespace App\Model\Order\UseCase\Create;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\UserRepository;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Handler
{
	private string $billingUrl;
	private string $notifyUrl;

	public function __construct(
		private readonly ParameterBagInterface $parameterBag,
		private readonly UserRepository $userRepository,
		private HttpClientInterface $client
	) {
		$this->billingUrl = $this->parameterBag->get('billingUrl');
		$this->notifyUrl = $this->parameterBag->get('notifyUrl');
	}

	/**
	 * @throws TransportExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface|ClientExceptionInterface
	 * @throws Exception
	 */
	public function handle(Command $command): void
	{
		$store = new FlockStore();
		$factory = new LockFactory($store);
		$lock = $factory->createLock('user-order-' . $command->userId);

		if ($lock->acquire()) {
			$response = $this->client->request('POST', $this->billingUrl.'/pay', [
				'json' => [
					'value' => $command->cost,
				],
				'headers' => [
					'X-UserId' => $command->userId,
				]
			]);

			$user = $this->userRepository->findById($command->userId);

			$decodedPayload = $response->toArray();

			if (!empty($decodedPayload['error'])) {
				$this->sendMessage($user, 'Не удалось оплатить заказ');

				$lock->release();
				throw new Exception($decodedPayload['error']);
			}

			$this->sendMessage($user, 'Заказ успешно оплачен');
			$lock->release();
		}
	}

	/**
	 * @throws TransportExceptionInterface
	 */
	private function sendMessage(User $user, string $message): void
	{
		$this->client->request('POST', $this->notifyUrl.'/add', [
			'json' => [
				'userId' => $user->getId(),
				'email' => $user->getEmail(),
				'text' => $message,
			],
		]);
	}
}