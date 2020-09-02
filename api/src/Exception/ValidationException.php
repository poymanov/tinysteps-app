<?php

declare(strict_types=1);

namespace App\Exception;

use DomainException;
use Throwable;

class ValidationException extends DomainException
{
    /**
     * @var string
     */
    private string $json;

    /**
     * @param string         $json
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $json, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->json = $json;
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return $this->json;
    }
}
