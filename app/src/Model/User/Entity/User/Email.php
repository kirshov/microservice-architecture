<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

class Email
{
	/**
	 * @var string
	 */
	private $email;

	/**
	 * Email constructor.
	 * @param string $email
	 */
	public function __construct(?string $email = null)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException('Incorrect email');
		}

		$this->email = mb_strtolower($email);
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param $other
	 * @return bool
	 */
	public function isEqual($other): bool
	{
		return $this->getEmail() === $other;
	}
}
