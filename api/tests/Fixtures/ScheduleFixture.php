<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\Lesson\Entity\Schedule\Id;
use App\Model\Lesson\Entity\Schedule\Schedule;
use App\Model\Lesson\Entity\Teacher\Teacher;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixture extends Fixture implements DependentFixtureInterface
{
    public const ID_1 = '00000000-0000-0000-0000-000000000001';

    public const ID_2 = '00000000-0000-0000-0000-000000000002';

    public const ID_3 = '00000000-0000-0000-0000-000000000003';

    public const REFERENCE_SCHEDULE_2 = 'schedule_2';

    public function load(ObjectManager $manager)
    {
        /** @var Teacher $teacher */
        $teacher = $this->getReference(TeacherFixture::REFERENCE_TEACHER_1);

        /** @var Teacher $teacherAnother */
        $teacherAnother = $this->getReference(TeacherFixture::REFERENCE_TEACHER_2);

        $schedule = new Schedule(
            new Id(self::ID_1),
            $teacher,
            new DateTimeImmutable('2030-12-12 12:15:00'),
            new DateTimeImmutable()
        );

        $manager->persist($schedule);

        $schedule = new Schedule(
            new Id(self::ID_2),
            $teacherAnother,
            new DateTimeImmutable('2030-12-11 12:15:00'),
            new DateTimeImmutable()
        );

        $manager->persist($schedule);

        $this->setReference(self::REFERENCE_SCHEDULE_2, $schedule);

        $schedule = new Schedule(
            new Id(self::ID_3),
            $teacherAnother,
            new DateTimeImmutable('1990-12-12 12:15:00'),
            new DateTimeImmutable()
        );

        $manager->persist($schedule);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            TeacherFixture::class,
        ];
    }
}
