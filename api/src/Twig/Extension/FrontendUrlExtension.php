<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Services\FrontendUrlGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FrontendUrlExtension extends AbstractExtension
{
    /**
     * @var FrontendUrlGenerator
     */
    private FrontendUrlGenerator $url;

    /**
     * @param FrontendUrlGenerator $url
     */
    public function __construct(FrontendUrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('frontend_url', [$this, 'url'])
        ];
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    public function url(string $path, array $params = []): string
    {
        return $this->url->generate($path, $params);
    }
}
