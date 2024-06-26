<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Twig;

use App\Crm\Application\Utils\Color;
use App\Crm\Constants;
use App\Crm\Domain\Entity\EntityWithMetaFields;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

final class Extensions extends AbstractExtension
{
    public const REPORT_DATE = 'Y-m-d';

    public function getFilters(): array
    {
        return [
            new TwigFilter('report_date', [$this, 'formatReportDate']),
            new TwigFilter('docu_link', [$this, 'documentationLink']),
            new TwigFilter('multiline_indent', [$this, 'multilineIndent']),
            new TwigFilter('color', [$this, 'color']),
            new TwigFilter('font_contrast', [$this, 'calculateFontContrastColor']),
            new TwigFilter('default_color', [$this, 'defaultColor']),
            new TwigFilter('nl2str', [$this, 'replaceNewline'], [
                'pre_escape' => 'html',
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('class_name', [$this, 'getClassName']),
            new TwigFunction('iso_day_by_name', [$this, 'getIsoDayByName']),
            new TwigFunction('random_color', [$this, 'randomColor']),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('number', function ($value): bool {
                return !\is_string($value) && is_numeric($value);
            }),
        ];
    }

    public function formatReportDate(\DateTimeInterface $dateTime): string
    {
        return $dateTime->format(self::REPORT_DATE);
    }

    public function getIsoDayByName(string $weekDay): int
    {
        $key = array_search(
            strtolower($weekDay),
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']
        );

        if ($key === false) {
            return 1;
        }

        return ++$key;
    }

    /**
     * Returns null instead of the default color if $defaultColor is not set to true.
     */
    public function color(EntityWithMetaFields $entity, bool $defaultColor = false): ?string
    {
        return (new Color())->getColor($entity, $defaultColor);
    }

    public function randomColor(?string $input = null): string
    {
        return (new Color())->getRandom($input);
    }

    public function calculateFontContrastColor(string $color): string
    {
        return (new Color())->getFontContrastColor($color);
    }

    public function defaultColor(?string $color = null): string
    {
        return $color ?? Constants::DEFAULT_COLOR;
    }

    /**
     * @param object $object
     */
    public function getClassName($object): ?string
    {
        if (!\is_object($object)) {
            return null;
        }

        return \get_class($object);
    }

    public function multilineIndent(?string $string, string $indent): string
    {
        if ($string === null || $string === '') {
            return '';
        }

        $parts = [];

        foreach (explode("\r\n", $string) as $part) {
            foreach (explode("\n", $part) as $tmp) {
                $parts[] = $tmp;
            }
        }

        $parts = array_map(function ($part) use ($indent) {
            return $indent . $part;
        }, $parts);

        return implode(PHP_EOL, $parts);
    }

    public function documentationLink(?string $url = ''): string
    {
        return Constants::HOMEPAGE . '/documentation/' . $url;
    }

    public function replaceNewline($input, string $newline)
    {
        if (!\is_string($input)) {
            return $input;
        }

        return str_replace(["\r\n", "\n", "\r"], $newline, $input);
    }
}
