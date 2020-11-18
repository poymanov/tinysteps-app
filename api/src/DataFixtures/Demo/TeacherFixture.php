<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use App\Model\Lesson\Entity\Teacher\Alias;
use App\Model\Lesson\Entity\Teacher\Description;
use App\Model\Lesson\Entity\Teacher\Price;
use App\Model\Lesson\Entity\Teacher\Rating;
use App\Model\Lesson\Entity\Teacher\Teacher;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\Lesson\TeacherBuilder;
use Ausi\SlugGenerator\SlugGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TeacherFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
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
        for ($i = 0; $i < 21; $i++) {
            /** @var User $user */
            $user    = $this->getReference('user_' . $i);
            $teacher = $this->buildTeacher($user);
            $manager->persist($teacher);
            $this->setReference('teacher_' . $i, $teacher);
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
            UserFixture::class,
        ];
    }

    /**
     * Создание объекта преподавателя
     *
     * @param User $user
     *
     * @return Teacher
     * @throws Exception
     */
    private function buildTeacher(User $user)
    {
        $alias = $this->slugGenerator->generate($user->getName()->getFull());

        return (new TeacherBuilder())
            ->withUserId($user->getId()->getValue())
            ->withAlias(new Alias($alias))
            ->withPrice($this->getRandomPrice())
            ->withRating($this->getRandomRating())
            ->withDescription($this->getDescription())
            ->build();
    }

    /**
     * Получение стоимости услуг преподавателя
     *
     * @return Price
     */
    private function getRandomPrice(): Price
    {
        $prices = [800, 900, 1000, 1100, 1200, 1300, 1400, 1500];

        $price = $prices[rand(0, count($prices) - 1)];

        return new Price($price);
    }

    /**
     * Получение рейтинга преподавателя
     *
     * @return Rating
     */
    private function getRandomRating(): Rating
    {
        $multiplier = 10;
        $rating     = mt_rand(1 * $multiplier, 5 * $multiplier) / $multiplier;

        return new Rating($rating);
    }

    /**
     * Получение описания преподавателя
     *
     * @return Description
     */
    private function getDescription(): Description
    {
        $descriptions = [
            'Я сейчас живу и преподаю в Европе, и у меня 10 лет опыт преподавания английского и французского языков в Москве, где я работал в институтах и подготовил студентам к ОГЭ, ЭГЭ, IELTS, TOEFL, DELF, DALF. Мои студенты отлично сдают международные экзамены. Смотрите мои отзывы. Удобная оплата на мой счет Сбербанк России. Я успешный репетитор, и я вам точно помогу!',
            'С удовольствием берусь заниматься со школьниками средней школы и старшеклассниками. Имею большой опыт подготовки к ОГЭ и ЕГЭ. Есть опыт подготовки в Лицей НИУ ВШЭ, готовлю к внутреннему экзамену (ДВИ) в МГИМО, РЭУ. Мои ученики поступают в МГУ, МГИМО, ВШЭ и другие вузы Москвы.',
            'Успешно помог 243 студентам достичь своих целей на английском языке. Обучены и подготовлены 119 отдельных студентов с различными английскими тестами. Помог 89 студентам с переселением в 13 разных стран. Помог 104 студентам принять участие в более чем 32 университетах в 10 странах. Написал или отредактировал 76 веб-сайтов, диссертаций, деловых контрактов, резюме и т.д. на английском языке.',
            'Имею большой опыт работы в HR глобальных корпораций, в моем CV — Allianz, Zurich, Deloitte. Профессиональный преподаватель английского, карьерный консультант, резюмерайтер. Помогаю построить карьеру в международной компании: — повысить уровень владения английским языком (бизнес, профессиональный и общий английский, подготовка к BEC, BULATS и IELTS); — выработать стратегию поиска работы, составить продающее резюме и подготовиться к собеседованию (на русском и английском). Спецкурс «Англ для HR».',
            'Помощь по школьной программе, изучить или вспомнить язык для путешествий, от 8л. IELTS, TOEFL, Cambridge exams, ОГЭ, ЕГЭ (все уровни- с нуля до продвинутого). Снятие языкового барьера: «все понимаю, не могу сказать», а также «вспомнить то, что проходили в школе» и «могу говорить, но хочу без ошибок». Подготовка по определенным аспектам экзаменов (Writing, Speaking, Listening, Reading). Возможность получения налогового вычета за обучение.',
            'Подготовка в лучшие вузы Москвы (МГУ, МГИМО, РУДН, ВШЭ и др.), в том числе к внутреннему экзамену. Подготовка к ЕГЭ. Развитие навыков разговорной речи. Все абитуриенты этого года поступили, куда хотели (результаты ЕГЭ - 98, 96, 94, 92 балла).',
            'Мною разработан ускоренный курс подготовки к ЕГЭ и ОГЭ. Мои ученики прекрасно сдают экзамены и поступают в те вузы, в какие планировали. Кроме того, я готовлю к ДВИ в гуманитарные вузы, такие как МГИМО, МГУ, Росс. экономический университет им. Плеханова, Иняз им. Мориса Тереза и т.д.'
        ];

        $description = $descriptions[rand(0, count($descriptions) - 1)];

        return new Description($description);
    }
}
