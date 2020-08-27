<?php

declare(strict_types=1);


namespace App\DataFixtures;


use App\Model\Lesson\Entity\Goal\Goal;
use App\Model\Lesson\Entity\Goal\Id;
use Ausi\SlugGenerator\SlugGenerator;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GoalFixture extends Fixture
{
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
        $names = ['Для переезда', 'Для учебы', 'Для путешествий', 'Для работы'];

        $sort = 0;

        foreach ($names as $name) {
            $alias = $this->slugGenerator->generate($name);
            $sort++;
            $date = new DateTimeImmutable();

            $goal = new Goal(Id::next(), $alias, $name, $sort, $date);
            $manager->persist($goal);
        }

        $manager->flush();
    }
}
