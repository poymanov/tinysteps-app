<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\ResetTokenizer;
use App\Tests\Builder\User\UserBuilder;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const NOT_CONFIRMED_UUID = '00000000-0000-0000-0000-000000000100';

    public const EXISTING_UUID = '00000000-0000-0000-0000-000000000101';

    public const ALREADY_REQUESTED_UUID = '00000000-0000-0000-0000-000000000102';

    public const REQUEST_RESET_TOKEN_UUID = '00000000-0000-0000-0000-000000000103';

    private ResetTokenizer $tokenizer;

    private PasswordHasher $hasher;

    /**
     * @param ResetTokenizer $tokenizer
     * @param PasswordHasher $hasher
     */
    public function __construct(ResetTokenizer $tokenizer, PasswordHasher $hasher)
    {
        $this->tokenizer = $tokenizer;
        $this->hasher    = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $existing = $this->getConfirmedUser()
            ->withId(new Id(self::EXISTING_UUID))
            ->viaEmail(new Email('existing-user@app.test'))
            ->withName(new Name('existing', 'user'))
            ->build();

        $manager->persist($existing);

        $notConfirmedPasswordHash = $this->hasher->hash('123qwe');
        $notConfirmed = (new UserBuilder())
            ->withId(new Id(self::NOT_CONFIRMED_UUID))
            ->viaEmail(new Email('not-confirmed-confirm@app.test'), $notConfirmedPasswordHash, 'not-confirmed-token')
            ->build();

        $manager->persist($notConfirmed);

        $alreadyRequestedToken = new ResetToken('789', (new DateTimeImmutable())->add(new DateInterval('P1Y')));

        $alreadyRequested = $this->getConfirmedUser()
            ->withId(new Id(self::ALREADY_REQUESTED_UUID))
            ->viaEmail(new Email('already-requested@app.test'))
            ->withResetToken($alreadyRequestedToken)
            ->build();

        $manager->persist($alreadyRequested);

        $expiredToken     = new ResetToken('456', new DateTimeImmutable());
        $withExpiredToken = $this->getConfirmedUser()
            ->viaEmail(new Email('expired-token@email.test'))
            ->withResetToken($expiredToken)
            ->build();

        $manager->persist($withExpiredToken);

        $resetToken             = new ResetToken('123', (new DateTimeImmutable())->add(new DateInterval('P1Y')));
        $requestedResetPassword = $this->getConfirmedUser()
            ->withId(new Id(self::REQUEST_RESET_TOKEN_UUID))
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
