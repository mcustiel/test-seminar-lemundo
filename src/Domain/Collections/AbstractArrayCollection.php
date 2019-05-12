<?php

namespace Lemundo\Translator\Domain\Collections;

use Countable;
use Iterator;

abstract class AbstractArrayCollection implements Iterator, Countable
{
    /** @var array */
    protected $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function current()
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key()
    {
        key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }
}
