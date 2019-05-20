<?php

namespace Lemundo\Translator\Tests\Persistence\Pdo;

use Lemundo\Translator\Persistence\Pdo\PdoConnection;
use PDO;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class PdoConnectionTest extends TestCase
{
    const DB_FILE = APP_PATH . '/var/data/phpunit.sqlite';

    /** @var string */
    private static $dsn;

    /** @var PdoConnection */
    private $connection;

    /** @var PDO */
    private static $pdoHelper;

    public static function setUpBeforeClass(): void
    {
        self::$dsn = 'sqlite:' . self::DB_FILE;
        self::$pdoHelper = new PDO(self::$dsn);
        self::$pdoHelper->exec('CREATE TABLE test(id INTEGER PRIMARY KEY, value VARCHAR(16))');
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdoHelper = null;
        if (file_exists(self::DB_FILE)) {
            unlink(self::DB_FILE);
        }
    }

    protected function setUp(): void
    {
        $this->connection = new PdoConnection(self::$dsn);
    }

    protected function tearDown(): void
    {
        $this->connection->disconnect();
    }

    public function testInsert()
    {
        $this->connection->connect();
        $this->connection->insert('test', ['id' => 1, 'value' => 'potato']);
        $res = self::$pdoHelper->query('SELECT id, value FROM test', PDO::FETCH_ASSOC);
        Assert::assertSame([['id' => '1', 'value' => 'potato']], $res->fetchAll());
        $this->connection->disconnect();
    }
}
