<?php

namespace Lemundo\Translator\Domain\Collections;

abstract class AbstractList extends AbstractArrayCollection
{
    protected function addValue($value): void
    {
        $this->data[] = $value;
    }
}
