<?php

declare(strict_types=1);

namespace App\Services;

class FrontendUrlGenerator
{
    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $uri
     * @param array $params
     * @return string
     */
    public function generate(string $uri, array $params = []): string
    {
        return $this->baseUrl
            . ($uri ? '/' . $uri : '')
            . ($params ? '?' . http_build_query($params) : '');
    }
}
