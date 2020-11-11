<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\Lesson\Entity\Lesson\Id;
use App\Model\Lesson\Entity\Lesson\Lesson;
use App\Model\Lesson\Entity\Schedule\Schedule;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class LessonFixture extends Fixture implements DependentFixtureInterface
{
    public const ID_1 = '00000000-0000-0000-0000-000000000001';

    /**
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var Schedule $schedule */
        $schedule = $this->getReference(ScheduleFixture::REFERENCE_SCHEDULE_2);

        $lesson = new Lesson(
            new Id(self::ID_1),
            UserFixture::ALREADY_REQUESTED_UUID,
            $schedule,
            new DateTimeImmutable(),
        );

        $manager->persist($lesson);
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            ScheduleFixture::class,
        ];
    }
}
