<?php

namespace Lemundo\Translator\Persistence;

use Lemundo\Translator\Domain\{TranslationId, Text, Locale};
use Lemundo\Translator\Domain\Collections\TranslationIdTextMap;

interface Translations
{
    public function add(TranslationId $translationId, Locale $locale, Text $text): void;

    public function get(TranslationId $translationId, Locale $locale): Text;

    public function getTranslations(Locale $locale): TranslationIdTextMap;
}
