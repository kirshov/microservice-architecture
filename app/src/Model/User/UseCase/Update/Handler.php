<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Update;

use App\Model\User\Entity\User\Email;
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
	public function handle(Command $command): void
	{
		$email = new Email($command->email);

        $user = $this->userRepository->findByEmail($email);

		if (null === $user) {
			throw new DomainException('User is not found');
		}

        $user->setName($command->name);

		$this->em->flush();
	}
}
