<?php

declare(strict_types=1);

use Phoenix\Exception\InvalidArgumentValueException;
use Phoenix\Migration\AbstractMigration;

final class Init extends AbstractMigration
{
	/**
	 * @throws InvalidArgumentValueException
	 */
	protected function up(): void
	{
		$this->table('notify_queue')
			->addColumn('id', 'biginteger')
			->addColumn('user_id', 'integer')
			->addColumn('email', 'string')
			->addColumn('text', 'text')
			->addColumn('status', 'integer')
			->addColumn('create_time', 'datetime')
			->create();

		$this->execute('CREATE SEQUENCE notify_queue_seq INCREMENT BY 1 MINVALUE 1 START 1');

	}

	protected function down(): void {
		$this->delete('notify_queue');
		$this->execute('DROP SEQUENCE notify_queue_seq');
	}
}