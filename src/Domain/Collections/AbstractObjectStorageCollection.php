<?php
namespace Lemundo\Translator\Domain\Collections;

use SplObjectStorage;
use Countable;
use Iterator;

abstract class AbstractObjectStorageCollection implements Iterator, Countable
{
    /** @var SplObjectStorage */
    protected $data;

    /**
     * AbstractObjectCollection constructor.
     */
    public function __construct()
    {
        $this->data = new SplObjectStorage();
    }

    public function current()
    {
        return $this->data->getInfo();
    }

    public function next(): void
    {
        $this->data->next();
    }

    public function key()
    {
        return $this->data->current();
    }

    public function valid(): bool
    {
        return $this->data->valid();
    }

    public function rewind(): void
    {
        $this->data->rewind();
    }

    public function isEmpty(): bool
    {
        return $this->data->count() === 0;
    }

    public function count(): int
    {
        return $this->data->count();
    }
}
