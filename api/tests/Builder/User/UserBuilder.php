<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use BadMethodCallException;
use DateTimeImmutable;
use Exception;

class UserBuilder
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $confirmed;

    /**
     * @var Role
     */
    private $role;


    /**
     * UserBuilder constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->id   = Id::next();
        $this->date = new DateTimeImmutable();
        $this->name = new Name('First', 'Last');
    }

    /**
     * @param Email|null  $email
     * @param string|null $hash
     * @param string|null $token
     *
     * @return $this
     */
    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone        = clone $this;
        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash  = $hash ?? 'hash';
        $clone->token = $token ?? 'token';

        return $clone;
    }

    /**
     * @return $this
     */
    public function confirmed(): self
    {
        $clone            = clone $this;
        $clone->confirmed = true;

        return $clone;
    }

    /**
     * @param Id $id
     *
     * @return $this
     */
    public function withId(Id $id): self
    {
        $clone     = clone $this;
        $clone->id = $id;

        return $clone;
    }

    /**
     * @param Name $name
     *
     * @return $this
     */
    public function withName(Name $name): self
    {
        $clone       = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function withAlias(string $alias): self
    {
        $clone        = clone $this;
        $clone->alias = $alias;

        return $clone;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function withRole(Role $role): self
    {
        $clone       = clone $this;
        $clone->role = $role;

        return $clone;
    }

    /**
     * @return User
     * @throws Exception
     */
    public function build(): User
    {
        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->name,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }
        }

        if (!$user) {
            throw new BadMethodCallException('Specify via method.');
        }

        return $user;
    }
}
