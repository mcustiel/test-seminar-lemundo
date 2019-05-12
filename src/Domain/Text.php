<?php

namespace Lemundo\Translator\Domain;

class Text
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->ensureNotEmpty($text);
        $this->text = $text;
    }

    public function asString(): string
    {
        return $this->text;
    }

    private function ensureNotEmpty(string $string): void
    {
        if (empty($string)) {
            throw new \InvalidArgumentException('Text can not be empty');
        }
    }
}