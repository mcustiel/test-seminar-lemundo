<?php

namespace Lemundo\Translator\Persistence\Pdo;

use PDO;
use PDOStatement;

class PdoResultIterator
{
    /** @var PDOStatement */
    private $result;

    /** @var array|false */
    private $currentValue;

    public function __construct(PDOStatement $result)
    {
        $this->result = $result;
        $this->currentValue = $this->result->fetch(PDO::FETCH_ASSOC);
    }

    public function next(): array
    {
        $row = $this->currentValue;
        $this->currentValue = $this->result->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function hasNext(): bool
    {
        $hasNext = $this->currentValue !== false;
        if (!$hasNext) {
            $this->result->closeCursor();
        }
        return $hasNext;
    }
}