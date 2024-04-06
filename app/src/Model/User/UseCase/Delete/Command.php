<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\Delete;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $id;
}
