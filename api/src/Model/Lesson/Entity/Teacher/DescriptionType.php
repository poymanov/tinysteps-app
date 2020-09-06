<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

class DescriptionType extends TextType
{
    public const NAME = 'lesson_teacher_description';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Description ? $value->getValue(): $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Status|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Description($value): null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
