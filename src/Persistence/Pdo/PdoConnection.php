<?php

namespace Lemundo\Translator\Persistence\Pdo;

use Lemundo\Translator\Persistence\Exception\AlreadyConnectedException;
use Lemundo\Translator\Persistence\Exception\DisconnectedException;
use Lemundo\Translator\Persistence\Exception\DuplicatedException;
use Lemundo\Translator\Persistence\Exception\NotFoundException;
use Lemundo\Translator\Persistence\Exception\PersistenceException;
use PDO;

class PdoConnection
{
    private const FIELD_SEPARATOR = ',';
    private const DUPLICATED_UNIQUE_ERROR = '23000';

    /** @var ?PDO */
    private $database;
    /** @var string */
    private $dsn;

    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function connect(): void
    {
        if ($this->database !== null) {
            throw new AlreadyConnectedException('There is already a database connection in place');
        }
        $this->database = new PDO($this->dsn);
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert(string $table, array $values): void
    {
        $this->ensureConnectedToDb();
        try {
            $query = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $table,
                implode(self::FIELD_SEPARATOR, array_keys($values)),
                rtrim(str_repeat('?,', \count($values)), self::FIELD_SEPARATOR)
            );
            $statement = $this->database->prepare($query);
            $statement->execute(array_values($values));
        } catch (\PDOException $e) {
            if ($e->getCode() === self::DUPLICATED_UNIQUE_ERROR) {
                throw new DuplicatedException('Unique constraint validation');
            }
            throw new PersistenceException('An error occurred while querying database', 0, $e);
        }
    }

    public function update(string $table, array $values, string $condition, array $conditionArgs = []): void
    {
        $this->ensureConnectedToDb();
        try {
            $query = $this->createUpdateQuery($table, $values, $condition);
            $statement = $this->database->prepare($query);
            $statement->execute(array_merge(array_values($values), $conditionArgs));
        } catch (\PDOException $e) {
            throw new PersistenceException('An error occurred while querying database', 0, $e);
        }
    }

    public function delete(string $table, string $condition, array $conditionArgs = []): void
    {
        $this->ensureConnectedToDb();
        try {
            $query = sprintf('DELETE FROM %s WHERE %s', $table, $condition);
            $statement = $this->database->prepare($query);
            $statement->execute($conditionArgs);
        } catch (\PDOException $e) {
            throw new PersistenceException('An error occurred while querying database', 0, $e);
        }
    }

    public function queryOne(string $query, array $binds = []): array
    {
        $this->ensureConnectedToDb();
        try {
            $statement = $this->database->prepare($query);
            $statement->execute($binds);
            $return = $statement->fetch(PDO::FETCH_ASSOC);
            if ($return === false) {
                throw new NotFoundException('No rows found');
            }
            $statement->closeCursor();

            return $return;
        } catch (\PDOException $e) {
            throw new PersistenceException('An error occurred while querying database', 0, $e);
        }
    }

    public function query(string $query, array $binds = []): PdoResultIterator
    {
        $this->ensureConnectedToDb();
        try {
            $statement = $this->database->prepare($query);
            $statement->execute($binds);

            return new PdoResultIterator($statement);
        } catch (\PDOException $e) {
            throw new PersistenceException('An error occurred while querying database', 0, $e);
        }
    }

    public function disconnect(): void
    {
        $this->database = null;
    }

    private function createUpdateQuery(string $table, array $values, string $condition): string
    {
        $sets = '';
        foreach (array_keys($values) as $key) {
            $sets .= sprintf('%s = ?,', $key);
        }

        return sprintf('UPDATE %s SET %s WHERE %s', $table, rtrim($sets, self::FIELD_SEPARATOR), $condition);
    }

    private function ensureConnectedToDb(): void
    {
        if ($this->database === null) {
            throw new DisconnectedException('Trying to query. But not connected to DB.');
        }
    }
}
