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
    public function load(ObjectManager $manager)
    {
        /** @var Teacher $teacher */
        $teacher = $this->getReference(TeacherFixture::REFERENCE_TEACHER_1);

        $date = new DateTimeImmutable();
        $date = $date->modify('+1 year');
        $date = $date->setTime(12, 15, 0);

        $schedule = new Schedule(Id::next(), $teacher, $date, new DateTimeImmutable());

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
