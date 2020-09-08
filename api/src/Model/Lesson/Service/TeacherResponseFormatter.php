<?php

declare(strict_types=1);

namespace App\Model\Lesson\Service;

use App\Model\Lesson\Entity\Teacher\Teacher;

class TeacherResponseFormatter
{
    /**
     * Представление преподавателя (все поля)
     *
     * @param Teacher $teacher
     *
     * @return array
     */
    public function full(Teacher $teacher): array
    {
        return [
            'id'          => $teacher->getId()->getValue(),
            'user_id'     => $teacher->getUserId(),
            'alias'       => $teacher->getAlias()->getValue(),
            'description' => $teacher->getDescription()->getValue(),
            'price'       => $teacher->getPrice()->getValue(),
            'rating'      => $teacher->getRating()->getValue(),
            'status'      => $teacher->getStatus()->getValue(),
            'created_at'  => $teacher->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
