<?php

namespace Lemundo\Translator\Domain\Collections;

use Lemundo\Translator\Domain\Text;
use Lemundo\Translator\Domain\TranslationId;

class TranslationIdTextMap extends AbstractMap
{
    public function set(TranslationId $id, Text $text): void
    {
        $this->setValue($id, $text);
    }
}
