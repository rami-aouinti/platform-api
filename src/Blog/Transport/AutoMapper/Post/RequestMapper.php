<?php

declare(strict_types=1);

namespace App\Blog\Transport\AutoMapper\Post;

use App\General\Domain\Enum\Language;
use App\General\Domain\Enum\Locale;
use App\General\Transport\AutoMapper\RestRequestMapper;
use InvalidArgumentException;

/**
 * @package App\Post
 */
class RequestMapper extends RestRequestMapper
{
    /**
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'title',
    ];

    protected function transformLanguage(string $language): Language
    {
        return Language::tryFrom($language) ?? throw new InvalidArgumentException('Invalid language');
    }

    protected function transformLocale(string $locale): Locale
    {
        return Locale::tryFrom($locale) ?? throw new InvalidArgumentException('Invalid locale');
    }
}
