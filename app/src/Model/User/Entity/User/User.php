<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users', uniqueConstraints: [
    new ORM\UniqueConstraint(columns: ['email']),
])]
#[ORM\HasLifecycleCallbacks]
class User
{
	const STATUS_NO_ACTIVE = 0;
	const STATUS_ACTIVE = 1;

    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "user_seq", initialValue: 1)]
	private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $create_time;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: "string", nullable: true)]
	private ?string $email = null;

    #[ORM\Column(type: 'smallint')]
	private ?int $status;

	/**
	 * User constructor.
	 * @throws \Exception
	 */
	public function __construct()
	{
		$this->create_time = new DateTimeImmutable();
		$this->status = self::STATUS_NO_ACTIVE;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
	 * @return mixed
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

    /**
     * @param Email $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
	 * @return int
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

    /**
     * @param int $status
     * @return User
     */
	public function setStatus(int $status): self
	{
		$this->status = $status;
        return $this;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->status === self::STATUS_ACTIVE;
	}
}
