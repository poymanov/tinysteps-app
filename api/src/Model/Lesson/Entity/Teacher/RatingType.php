<?php

declare(strict_types=1);

namespace App\Model\Lesson\Entity\Teacher;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;

class RatingType extends FloatType
{
    public const NAME = 'lesson_teacher_rating';

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|float
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Rating ? $value->getValue() : $value;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return Status|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Rating($value) : null;
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
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
