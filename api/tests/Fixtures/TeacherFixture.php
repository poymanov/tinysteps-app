<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Id;
use App\Model\Lesson\Entity\Teacher\Status;
use App\Tests\Builder\Lesson\TeacherBuilder;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TeacherFixture extends Fixture
{
    public const TEACHER_1_ID = '00000000-0000-0000-0000-000000000001';

    public const TEACHER_2_ID = '00000000-0000-0000-0000-000000000002';

    public const TEACHER_3_ID = '00000000-0000-0000-0000-000000000003';

    public const REFERENCE_TEACHER_1 = 'teacher_1';

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
            ->withAlias(new Alias('existing-user'))
            ->withCreatedAt(new DateTimeImmutable('2020-01-01 15:00:00'))
            ->build();

        $manager->persist($teacher);

        $this->setReference(self::REFERENCE_TEACHER_1, $teacher);

        $teacher = (new TeacherBuilder())
            ->withId(new Id(self::TEACHER_2_ID))
            ->withUserId(UserFixture::ALREADY_REQUESTED_UUID)
            ->withAlias(new Alias('already-request-user'))
            ->withStatus(Status::archived())
            ->withCreatedAt(new DateTimeImmutable('2020-01-01 16:00:00'))
            ->build();

        $manager->persist($teacher);

        $teacher = (new TeacherBuilder())
            ->withId(new Id(self::TEACHER_3_ID))
            ->withUserId(UserFixture::REQUEST_RESET_TOKEN_UUID)
            ->withAlias(new Alias('request-reset-token'))
            ->withCreatedAt(new DateTimeImmutable('2020-01-01 17:00:00'))
            ->build();

        $manager->persist($teacher);

        $manager->flush();
    }
}
