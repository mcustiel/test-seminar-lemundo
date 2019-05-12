<?php

namespace Lemundo\Translator\Persistence\Pdo;

use PDO;
use RuntimeException;

class PdoConnection
{
    private const FIELD_SEPARATOR = ',';

    /** @var PDO */
    private $database;
    /** @var string */
    private $dsn;

    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function connect()
    {
        if ($this->database !== null) {
            throw new RuntimeException('There is already a database connection in place');
        }
        $this->database = new PDO($this->dsn);
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert(string $table, array $values): void
    {
        $this->ensureConnectedToDb();
        $query = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(self::FIELD_SEPARATOR, array_keys($values)),
            rtrim(str_repeat('?,', count($values)), self::FIELD_SEPARATOR)
        );
        $statement = $this->database->prepare($query);
        $statement->execute(array_values($values));
    }

    public function queryOne(string $query, array $binds = []): array
    {
        $this->ensureConnectedToDb();
        $statement = $this->database->prepare($query);
        $statement->execute($binds);
        $return = $statement->fetch(PDO::FETCH_ASSOC);
        if ($return === false) {
            throw new RuntimeException('No rows found');
        }
        $statement->closeCursor();
        return $return;
    }

    public function query(string $query, array $binds = []): PdoResultIterator
    {
        $this->ensureConnectedToDb();
        $statement = $this->database->prepare($query);
        $statement->execute($binds);

        return new PdoResultIterator($statement);
    }

    public function disconnect(): void
    {
        $this->database = null;
    }

    private function ensureConnectedToDb()
    {
        if ($this->database === null) {
            throw new \RuntimeException('Trying to query. But not connected to DB.');
        }
    }
}
