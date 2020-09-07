<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Tests\Builder\Lesson\TeacherBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TeacherFixture extends Fixture
{
    public const TEACHER_1_ID = '00000000-0000-0000-0000-000000000001';

    /**
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $teacher = (new TeacherBuilder())
            ->withId(new Id(self::TEACHER_1_ID))
            ->withUserId(UserFixture::EXISTING_UUID)
            ->withAlias(new Alias('existing-user'))->build();

        $manager->persist($teacher);

        $manager->flush();
    }
}
