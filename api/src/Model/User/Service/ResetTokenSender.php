<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email as UserEmail;
use App\Model\User\Entity\User\ResetToken;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ResetTokenSender
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environment;
     */
    private $twig;

    /**
     * ResetTokenSender constructor.
     *
     * @param MailerInterface $mailer
     * @param Environment     $twig
     */
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig   = $twig;
    }

    /**
     * @param UserEmail  $email
     * @param ResetToken $token
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function send(UserEmail $email, ResetToken $token): void
    {
        $subject = 'Сброс пароля';
        $email   = (new Email())
            ->subject($subject)
            ->to($email->getValue())
            ->html($this->twig->render('mail/user/reset.html.twig', ['token' => $token->getToken()]));

        try {
            $this->mailer->send($email);
        } catch (TransportException $e) {
            throw new RuntimeException('Невозможно отправить сообщение.');
        }
    }
}
