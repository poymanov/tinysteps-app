<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Name;
use App\Tests\Builder\User\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
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
