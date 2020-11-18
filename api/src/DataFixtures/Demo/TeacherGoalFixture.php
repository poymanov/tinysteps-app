<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use App\DataFixtures\GoalFixture;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\Lesson\Entity\TeacherGoal\Id;
use App\Model\Lesson\Entity\TeacherGoal\TeacherGoal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TeacherGoalFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 21; $i++) {
            /** @var Teacher $teacher */
            $teacher = $this->getReference('teacher_' . $i);

            $goals = $this->getGoals();

            foreach ($goals as $goal) {
                $teacherGoal = $this->buildTeacherGoal($teacher->getId()->getValue(), $goal);
                $manager->persist($teacherGoal);
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['demo'];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            TeacherFixture::class,
        ];
    }

    /**
     * Создание объекта связи преподаватель-цель
     *
     * @param string $teacherId
     * @param string $goalId
     *
     * @return TeacherGoal
     * @throws Exception
     */
    private function buildTeacherGoal(string $teacherId, string $goalId): TeacherGoal
    {
        return new TeacherGoal(Id::next(), $teacherId, $goalId);
    }

    /**
     * Получение набора целей для преподавателя
     *
     * @return array
     */
    private function getGoals(): array
    {
        $goals = [
            GoalFixture::GOAL_1_ID,
            GoalFixture::GOAL_2_ID,
            GoalFixture::GOAL_3_ID,
            GoalFixture::GOAL_4_ID,
        ];

        shuffle($goals);

        $goalsCount = rand(1, 4);

        return array_slice($goals, 0, $goalsCount);
    }
}
