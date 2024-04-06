<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Create;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{

	/**
	 * RequestCommand constructor.
	 * @param UserRepository $userRepository
	 */
	public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
	}

    /**
     * @param Command $command
     * @return int|null
     */
	public function handle(Command $command): User
	{
		$email = new Email($command->email);

		if ($this->userRepository->hasByEmail($email)) {
			throw new \DomainException('User already exist');
		}

		$user = new User();
        $user->setName($command->name);
        $user->setEmail($email->getEmail());
        $user->setStatus(User::STATUS_ACTIVE);

        $this->userRepository->add($user);
        $this->entityManager->flush();

        return $user;
	}
}
