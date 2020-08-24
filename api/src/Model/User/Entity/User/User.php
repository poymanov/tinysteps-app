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
    private Id $id;

    /**
     * @var Email
     * @ORM\Column(type="user_user_email", nullable=false)
     */
    private Email $email;

    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private Name $name;

    /**
     * @var Status
     * @ORM\Column(type="user_user_status")
     */
    private Status $status;

    /**
     * @var Role
     * @ORM\Column(type="user_user_role", length=16)
     */
    private Role $role;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="password_hash")
     */
    private ?string $passwordHash;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private ?string $confirmToken;

    /**
     * @var ResetToken|null
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private ?ResetToken $resetToken = null;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

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
     * @return ResetToken|null
     */
    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
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
     * Запрос сброса пароля
     *
     * @param ResetToken        $token
     * @param DateTimeImmutable $date
     */
    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->getStatus()->isActive()) {
            throw new DomainException('Пользователь ещё не активен.');
        }

        if (!$this->email) {
            throw new DomainException('Email не определен.');
        }

        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Сброс пароля уже запрошен.');
        }

        $this->resetToken = $token;
    }

    /**
     * Сброс пароля
     *
     * @param DateTimeImmutable $date
     * @param string            $hash
     *
     * @return void
     */
    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new DomainException('Сброс пароля не был запрошен.');
        }

        if ($this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Токен сброса пароля уже истек.');
        }

        $this->passwordHash = $hash;
        $this->resetToken   = null;
    }
}
