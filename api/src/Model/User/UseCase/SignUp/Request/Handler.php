<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\SignUpConfirmTokenSender;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     * @var SignUpConfirmTokenizer
     */
    private $tokenizer;

    /**
     * @var SignUpConfirmTokenSender
     */
    private $sender;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param UserRepository           $users
     * @param PasswordHasher           $hasher
     * @param SignUpConfirmTokenizer   $tokenizer
     * @param SignUpConfirmTokenSender $sender
     * @param Flusher                  $flusher
     */
    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        SignUpConfirmTokenizer $tokenizer,
        SignUpConfirmTokenSender $sender,
        Flusher $flusher
    ) {
        $this->users     = $users;
        $this->hasher    = $hasher;
        $this->tokenizer = $tokenizer;
        $this->sender    = $sender;
        $this->flusher   = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws Exception
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByMail($email)) {
            throw new DomainException('Пользователь уже существует.');
        }

        $user = User::signUpByEmail(
            Id::next(),
            new DateTimeImmutable(),
            new Name($command->firstName, $command->lastName),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->users->add($user);
        $this->flusher->flush();
        $this->sender->send($email, $token);
    }
}
