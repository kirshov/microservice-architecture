<?php
declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use Doctrine\ORM;

class UserRepository
{
	/** @var ORM\EntityManagerInterface */
	private $entityManager;

	/** @var ORM\EntityRepository */
	private $repository;

	/**
	 * UserRepository constructor.
	 * @param ORM\EntityManagerInterface $entityManager
	 */
	public function __construct(ORM\EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->repository = $entityManager->getRepository(User::class);
	}

	/**
	 * @param int $id
	 * @return User|null
	 */
	public function findById(int $id): ?User
	{
		return $this->repository->find($id);
	}

	/**
	 * @param Email $email
	 * @return User|null
	 */
	public function findByEmail(Email $email): ?User
	{
		return $this->repository->findOneBy(['email' => $email->getEmail()]);
	}

	/**
	 * @param Email $email
	 * @return bool
	 */
	public function hasByEmail(Email $email): bool
	{
		return $this->repository->createQueryBuilder('t')
			->select('COUNT(t.id)')
			->andWhere('t.email = :email')
			->setParameter(':email', $email->getEmail())
			->getQuery()
			->getSingleScalarResult() > 0;
	}

	/**
	 * @param User $user
	 */
	public function add(User $user): void
	{
		$this->entityManager->persist($user);
	}
}
