<?php
namespace Lemundo\Translator\Domain\Collections;

abstract class AbstractMap extends AbstractObjectStorageCollection
{
    protected function hasKey($key): bool
    {
        return isset($this->data[$key]);
    }

    protected function setValue($key, $value): void
    {
        $this->data[$key] = $value;
    }

    protected function getByKey($key)
    {
        return $this->data[$key];
    }
}
