<?php

namespace Lemundo\Translator\Domain;

use InvalidArgumentException;

class TranslationId
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 32;

    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->ensureNotEmpty($id);
        $this->ensureAlphaNumeric($id);
        $this->ensureLength($id);
        $this->id = $id;
    }

    public function asString(): string
    {
        return $this->id;
    }

    private function ensureNotEmpty(string $string): void
    {
        if (empty($string)) {
            throw new InvalidArgumentException('Translation id can not be empty');
        }
    }

    private function ensureAlphaNumeric(string $string): void
    {
        if (!ctype_alnum($string)) {
            throw new InvalidArgumentException(
                sprintf('Translation id must be an alphanumeric string. Got %s', $string)
            );
        }
    }

    private function ensureLength(string $string): void
    {
        $length = \strlen($string);
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf(
                    'Translation id length expected to be between %d and %d. Got %s with length %d',
                    self::MIN_LENGTH,
                    self::MAX_LENGTH,
                    $string,
                    $length
                )
            );
        }
    }
}
