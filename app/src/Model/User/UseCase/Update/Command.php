<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Update;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $id;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	public $name;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	public $email;

}
