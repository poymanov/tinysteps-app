<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Service\PasswordHasher;
use App\Tests\Builder\User\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const USER_1_ID = '00000000-0000-0000-0000-000000000001';

    private PasswordHasher $hasher;

    /**
     * @param PasswordHasher $hasher
     */
    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $userCredentials = self::userCredentials();
        $hash = $this->hasher->hash($userCredentials['password']);

        $user = (new UserBuilder())
            ->withId(new Id(self::USER_1_ID))
            ->viaEmail(new Email($userCredentials['email']), $hash)
            ->confirmed()
            ->build();

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @return array
     */
    public static function userCredentials(): array
    {
        return [
            'email' => 'user@app.test',
            'password'   => '123qwe',
        ];
    }
}
