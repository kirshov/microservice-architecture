<?php

declare(strict_types=1);

namespace App\Storages;

class UserStorage
{
	private static ?int $userId = null;

	public static function getUserId(): ?int
	{
		return self::$userId;
	}

	public static function setUserId(?int $userId): void
	{
		self::$userId = $userId;
	}
}