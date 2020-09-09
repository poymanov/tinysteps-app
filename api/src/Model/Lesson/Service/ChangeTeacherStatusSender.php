<?php

declare(strict_types=1);

namespace App\Model\Lesson\Service;

use App\Model\Lesson\Entity\Teacher\Status;
use App\Model\User\Entity\User\Email as UserEmail;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ChangeTeacherStatusSender
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
     * @param UserEmail $email
     * @param Status    $status
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function send(UserEmail $email, Status $status): void
    {
        $subject = 'Изменение статуса профиля преподавателя';
        $email   = (new Email())
            ->subject($subject)
            ->to($email->getValue())
            ->html($this->twig->render('mail/lesson/teacher/change-status.html.twig', [
                'label' => $status->getLabel(),
            ]));

        try {
            $this->mailer->send($email);
        } catch (TransportException $e) {
            throw new RuntimeException('Невозможно отправить сообщение.');
        }
    }
}
