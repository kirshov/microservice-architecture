<?php

declare(strict_types=1);

namespace App\DTO;

class UserSignin
{
	public function __construct(
		public ?string $email,
		public ?string $name,
		public ?string $password,
	) {}
}