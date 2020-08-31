<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\Lesson\Entity\Goal\Alias;
use App\Model\Lesson\Entity\Goal\Id;
use App\Tests\Builder\Lesson\GoalBuilder;
use Ausi\SlugGenerator\SlugGenerator;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GoalFixture extends Fixture
{
    public const GOAL_1_ID = '00000000-0000-0000-0000-000000000001';

    public const GOAL_2_ID = '00000000-0000-0000-0000-000000000002';

    public const GOAL_3_ID = '00000000-0000-0000-0000-000000000003';

    public const GOAL_4_ID = '00000000-0000-0000-0000-000000000004';

    /**
     * @var SlugGenerator
     */
    private SlugGenerator $slugGenerator;

    /**
     * @param SlugGenerator $slugGenerator
     */
    public function __construct(SlugGenerator $slugGenerator)
    {
        $this->slugGenerator = $slugGenerator;
    }

    public function load(ObjectManager $manager)
    {
        $goals = [
            self::GOAL_1_ID => [
                'name'      => 'Для переезда',
                'createdAt' => '2020-01-01 10:00:00',
            ],
            self::GOAL_2_ID => [
                'name'      => 'Для учебы',
                'createdAt' => '2020-01-02 10:00:00',
            ],
            self::GOAL_3_ID => [
                'name'      => 'Для путешествий',
                'createdAt' => '2020-01-03 10:00:00',
            ],
            self::GOAL_4_ID => [
                'name'      => 'Для работы',
                'createdAt' => '2020-01-04 10:00:00',
            ],
        ];

        $sort = 0;

        foreach ($goals as $uuid => $properties) {
            $name = $properties['name'];
            $alias = $this->slugGenerator->generate($name);
            $sort++;

            $goal = (new GoalBuilder())
                ->withId(new Id($uuid))
                ->withAlias(new Alias($alias))
                ->withName($name)
                ->withSort($sort)
                ->withCreatedAt(new DateTimeImmutable($properties['createdAt']))
                ->build();

            $manager->persist($goal);
        }

        $manager->flush();
    }
}
