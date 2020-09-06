<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Tests\Builder\Lesson\TeacherBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TeacherFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $teacher = (new TeacherBuilder())
            ->withUserId(UserFixture::EXISTING_UUID)
            ->withAlias(new Alias('existing-user'))->build();

        $manager->persist($teacher);

        $manager->flush();
    }
}
