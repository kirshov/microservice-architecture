<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Update;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class Handler
{
	/**
	 * RequestCommand constructor.
	 * @param EntityManagerInterface $em
	 * @param UserRepository $userRepository
	 */
	public function __construct(
		private readonly EntityManagerInterface $em,
		private readonly UserRepository $userRepository
	) {
	}

	/**
	 * @param Command $command
	 */
	public function handle(Command $command): User
	{
        $user = $this->userRepository->findById($command->id);

		if (null === $user) {
			throw new DomainException('User is not found');
		}

		if (!empty($command->name)) {
			$user->setName($command->name);
		}

		if (!empty($command->email)) {
			$user->setEmail($command->email);
		}

		$this->em->flush();

		return $user;
	}
}
