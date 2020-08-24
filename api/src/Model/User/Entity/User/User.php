<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"email"}),
 * })
 */
class User
{
    /**
     * @var Id
     * @ORM\Column(type="user_user_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Email
     * @ORM\Column(type="user_user_email", nullable=false)
     */
    private $email;

    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private $name;

    /**
     * @var Status
     * @ORM\Column(type="user_user_status")
     */
    private $status;

    /**
     * @var Role
     * @ORM\Column(type="user_user_role", length=16)
     */
    private $role;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="password_hash")
     */
    private $passwordHash;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private $confirmToken;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @param Id                $id
     * @param DateTimeImmutable $date
     * @param Name              $name
     */
    private function __construct(Id $id, DateTimeImmutable $date, Name $name)
    {
        $this->id        = $id;
        $this->createdAt = $date;
        $this->name      = $name;
        $this->role      = Role::user();
    }

    /**
     * Регистрация через email
     *
     * @param Id                $id
     * @param DateTimeImmutable $date
     * @param Name              $name
     * @param Email             $email
     * @param string            $hash
     * @param string            $token
     *
     * @return $this
     */
    public static function signUpByEmail(Id $id, DateTimeImmutable $date, Name $name, Email $email, string $hash, string $token): self
    {
        $user               = new self($id, $date, $name);
        $user->email        = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status       = Status::wait();

        return $user;
    }

    /**
     * Подтверждение учетной записи пользователя
     */
    public function confirmSignUp(): void
    {
        if (!$this->getStatus()->isWait()) {
            throw new DomainException('Токен уже подтвержден.');
        }

        $this->status       = Status::active();
        $this->confirmToken = null;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * @return string|null
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
