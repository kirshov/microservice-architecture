<?php

namespace App\Model\Order\UseCase\Create;

class Command
{
	public int $userId;
	public float $cost;
	public string $name;
}