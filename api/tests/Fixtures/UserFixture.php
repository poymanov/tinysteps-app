<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Service\ResetTokenizer;
use App\Tests\Builder\User\UserBuilder;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    private ResetTokenizer $tokenizer;

    /**
     * @param ResetTokenizer $tokenizer
     */
    public function __construct(ResetTokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    public function load(ObjectManager $manager)
    {
        $existing = $this->getConfirmedUser()
            ->viaEmail(new Email('existing-user@app.test'))
            ->withName(new Name('existing', 'user'))
            ->build();

        $manager->persist($existing);

        $notConfirmed = (new UserBuilder())
            ->viaEmail(new Email('not-confirmed-confirm@app.test'), null, 'not-confirmed-token')
            ->build();

        $manager->persist($notConfirmed);

        $alreadyRequestedToken = new ResetToken('789', (new DateTimeImmutable())->add(new DateInterval('P1Y')));

        $alreadyRequested = $this->getConfirmedUser()
            ->viaEmail(new Email('already-requested@app.test'))
            ->withResetToken($alreadyRequestedToken)
            ->build();

        $manager->persist($alreadyRequested);

        $expiredToken = new ResetToken('456', new DateTimeImmutable());
        $withExpiredToken = $this->getConfirmedUser()
            ->viaEmail(new Email('expired-token@email.test'))
            ->withResetToken($expiredToken)
            ->build();

        $manager->persist($withExpiredToken);

        $resetToken   = new ResetToken('123', (new DateTimeImmutable())->add(new DateInterval('P1Y')));
        $requestedResetPassword = $this->getConfirmedUser()
            ->viaEmail(new Email('request-reset-token@email.test'))
            ->withResetToken($resetToken)
            ->build();

        $manager->persist($requestedResetPassword);

        $manager->flush();
    }

    /**
     * @return UserBuilder
     */
    private function getConfirmedUser(): UserBuilder
    {
        return (new UserBuilder())->confirmed();
    }
}
