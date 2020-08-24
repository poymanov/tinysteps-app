<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use RuntimeException;

class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);

        if ($hash === false) {
            throw new RuntimeException('Unable to generate hash.');
        }

        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
