<?php

declare(strict_types=1);

namespace App\Console;

use App\Repository\NotifyRepository;
use Psr\Container\ContainerInterface;

class NotifySender
{
	protected NotifyRepository $repository;

	public function __construct(NotifyRepository $repository)
	{
		$this->repository = $repository;
	}

	public function __invoke()
	{
		foreach ($this->repository->getItems() as $item) {
			$this->repository->setDone($item['id']);
		}
	}
}