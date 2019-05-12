<?php

namespace Lemundo\Translator\Domain;

use InvalidArgumentException;

class Locale
{
    public const ENGLISH = 'en_GB';
    public const GERMAN = 'de_DE';
    public const SWISS_GERMAN = 'de_CH';
    public const AUSTRIAN_GERMAN = 'de_AT';

    private const VALID_LOCALES = [
        self::ENGLISH,
        self::GERMAN,
        self::SWISS_GERMAN,
        self::AUSTRIAN_GERMAN,
    ];

    /** @var string */
    private $locale;

    public function __construct(string $locale)
    {
        $this->ensureValidLocale($locale);
        $this->locale = $locale;
    }

    public static function getLocales() : array
    {
        return self::VALID_LOCALES;
    }

    public function asString(): string
    {
        return $this->locale;
    }

    private function ensureValidLocale(string $locale): void
    {
        if (!in_array($locale, self::VALID_LOCALES)) {
            throw new InvalidArgumentException(sprintf('Invalid locale: %s', $locale));
        }
    }
}