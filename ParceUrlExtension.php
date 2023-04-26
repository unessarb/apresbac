<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ParceUrlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('parse_url', [$this, 'parseUrl']),
        ];
    }

    public function parseUrl($value)
    {
        if (!$value) return $value;
        parse_str(parse_url($value, PHP_URL_QUERY), $my_array_of_vars);
        return $my_array_of_vars['v'];
    }
}
