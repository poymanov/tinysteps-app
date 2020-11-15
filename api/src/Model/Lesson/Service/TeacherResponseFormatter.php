<?php

declare(strict_types=1);

namespace App\Model\Lesson\Service;

use App\ReadModel\Lesson\TeacherView;
use DateTimeImmutable;
use Exception;

class TeacherResponseFormatter
{
    /**
     * Представление преподавателя (все поля)
     *
     * @param TeacherView $teacher
     *
     * @return array
     * @throws Exception
     */
    public function full(TeacherView $teacher): array
    {
        return [
            'id'          => $teacher->id,
            'user_id'     => $teacher->user_id,
            'alias'       => $teacher->alias,
            'name'        => [
                'first' => $teacher->name_first,
                'last'  => $teacher->name_last,
            ],
            'description' => $teacher->description,
            'price'       => (int) $teacher->price,
            'rating'      => (float) $teacher->rating,
            'status'      => $teacher->status,
            'created_at'  => (new DateTimeImmutable($teacher->created_at))->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Представление списка преподавателей (все поля)
     *
     * @param TeacherView[] $teachers
     *
     * @return array
     * @throws Exception
     */
    public function fullList(array $teachers): array
    {
        $teachersList = [];

        foreach ($teachers as $teacher) {
            $teachersList[] = $this->full($teacher);
        }

        return $teachersList;
    }
}
