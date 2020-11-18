<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use Ausi\SlugGenerator\SlugGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    /** @var SlugGenerator */
    private SlugGenerator $slugGenerator;

    /**
     * @param SlugGenerator $slugGenerator
     */
    public function __construct(SlugGenerator $slugGenerator)
    {
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $userNames = $this->getUserNames();

        foreach ($userNames as $key => $name) {
            $user = $this->buildUser($name[0], $name[1]);
            $manager->persist($user);
            $this->setReference('user_' . $key, $user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['demo'];
    }

    /**
     * @return UserBuilder
     */
    private function getConfirmedUser(): UserBuilder
    {
        return (new UserBuilder())->confirmed();
    }

    /**
     * Создание объекта пользователя
     *
     * @param string $firstName
     * @param string $lastName
     *
     * @return User
     * @throws Exception
     */
    private function buildUser(string $firstName, string $lastName): User
    {
        $fullName = $firstName . ' ' . $lastName;
        $slug = $this->slugGenerator->generate($fullName);
        $email = $slug . '@app.test';

        return $this->getConfirmedUser()
            ->viaEmail(new Email($email))
            ->withName(new Name($firstName, $lastName))
            ->build();
    }

    /**
     * Получение списка имен пользователей
     *
     * @return array
     */
    private function getUserNames(): array
    {
        return [
            ['Артём', 'Белов'], ['Павел', 'Васильев'], ['Михаил', 'Морозов'],
            ['Дмитрий', 'Романов'], ['Валерия', 'Некрасова'], ['Михаил', 'Емельянов'],
            ['Вероника', 'Соколова'], ['Егор', 'Козлов'], ['Мария', 'Кошелева'],
            ['Иван', 'Ефремов'], ['Дарья', 'Носова'], ['Алексей', 'Третьяков'],
            ['Александра', 'Орехова'], ['Дарья', 'Кулагина'], ['Михаил', 'Горохов'],
            ['Анна', 'Софронова'], ['Василиса', 'Блинова'], ['Василиса', 'Орлова'],
            ['Максим', 'Крылов'], ['Валерия', 'Нестерова'], ['Милана', 'Ковалева']
        ];
    }
}
