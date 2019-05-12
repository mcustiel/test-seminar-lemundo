<?php

namespace Lemundo\Translator\Domain\Collections;

use Lemundo\Translator\Domain\Locale;

class LocaleList extends AbstractList
{
    public function add(Locale $locale): void
    {
        $this->addValue($locale);
    }
}
