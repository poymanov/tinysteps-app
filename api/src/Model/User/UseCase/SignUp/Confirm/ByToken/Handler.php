<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm\ByToken;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;
use DomainException;

class Handler
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param Flusher $flusher
     */
    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByConfirmToken($command->token)) {
            throw new DomainException('Неизвестный токен.');
        }

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}
