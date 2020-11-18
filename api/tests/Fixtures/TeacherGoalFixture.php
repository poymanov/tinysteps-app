<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\DataFixtures\GoalFixture;
use App\Model\Lesson\Entity\TeacherGoal\Id;
use App\Model\Lesson\Entity\TeacherGoal\TeacherGoal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TeacherGoalFixture extends Fixture implements FixtureGroupInterface
{
    public const TEACHER_GOAL_1_ID = '00000000-0000-0000-0000-000000000001';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $teacherGoal = new TeacherGoal(
            new Id(self::TEACHER_GOAL_1_ID),
            TeacherFixture::TEACHER_1_ID,
            GoalFixture::GOAL_2_ID
        );

        $manager->persist($teacherGoal);

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
