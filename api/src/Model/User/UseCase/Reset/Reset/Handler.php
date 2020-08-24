<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;
use Exception;

class Handler
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var PasswordHasher
     */
    private $hasher;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     *
     * @param UserRepository $users
     * @param PasswordHasher $hasher
     * @param Flusher        $flusher
     */
    public function __construct(UserRepository $users, PasswordHasher $hasher, Flusher $flusher)
    {
        $this->users   = $users;
        $this->hasher  = $hasher;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByResetToken($command->token)) {
            throw new DomainException('Неизвестный токен.');
        }

        $user->passwordReset(new DateTimeImmutable(), $this->hasher->hash($command->password));

        $this->flusher->flush();
    }

}
