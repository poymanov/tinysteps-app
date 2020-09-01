<?php

declare(strict_types=1);

namespace App\Model\Lesson\Service;

use App\Model\Lesson\Entity\Goal\Goal;

class GoalResponseFormatter
{
    /**
     * Представление цели обучения (все поля)
     *
     * @param Goal $goal
     *
     * @return array
     */
    public function full(Goal $goal): array
    {
        return [
            'id'         => $goal->getId()->getValue(),
            'alias'      => $goal->getAlias()->getValue(),
            'name'       => $goal->getName(),
            'status'     => $goal->getStatus()->getValue(),
            'sort'       => $goal->getSort(),
            'created_at' => $goal->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
