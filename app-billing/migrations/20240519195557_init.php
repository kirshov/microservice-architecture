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
		$this->table('billing_billing')
			->addColumn('id', 'biginteger')
			->addColumn('user_id', 'integer')
			->addColumn('value', 'integer')
			->addColumn('create_time', 'datetime')
			->create();

		$this->execute('CREATE SEQUENCE billing_seq INCREMENT BY 1 MINVALUE 1 START 1');

	}

	protected function down(): void {
		$this->delete('billing');
		$this->execute('DROP SEQUENCE billing_seq');
	}
}