<?php

namespace Lemundo\Translator\Persistence;

use Lemundo\Translator\Domain\Collections\TranslationIdTextMap;
use Lemundo\Translator\Domain\Locale;
use Lemundo\Translator\Domain\Text;
use Lemundo\Translator\Domain\TranslationId;

interface Translations
{
    public function add(TranslationId $translationId, Locale $locale, Text $text): void;

    public function get(TranslationId $translationId, Locale $locale): Text;

    public function getTranslations(Locale $locale): TranslationIdTextMap;
}
