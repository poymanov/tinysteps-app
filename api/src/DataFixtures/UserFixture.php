<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Service\PasswordHasher;
use App\Tests\Builder\User\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
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
        $hash = $this->hasher->hash('123qwe');

        $user = (new UserBuilder())
            ->viaEmail(new Email('user@app.test'), $hash)
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
            'PHP_AUTH_USER' => 'user@app.test',
            'PHP_AUTH_PW'   => '123qwe',
        ];
    }
}
