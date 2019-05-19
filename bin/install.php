<?php

const BASE_PATH = __DIR__ . '/..';
require BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Lemundo\Translator\Config;
use Lemundo\Translator\Factory;

$dotenv = Dotenv::create(BASE_PATH);
$dotenv->load();

$config = new Config(require BASE_PATH . '/config/config.php');

$factory = new Factory($config);
$dbFile = str_replace('sqlite:', '', $config->getDatabaseConnectionDsn());
echo 'Checking database file ' . $dbFile . '...' . PHP_EOL;
if (!file_exists($dbFile)) {
    echo 'Creating database...' . PHP_EOL;
    touch($dbFile);
    $database = new PDO($config->getDatabaseConnectionDsn());

    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->exec(file_get_contents(BASE_PATH . '/resources/install.sql'));
    echo 'Database created...' . PHP_EOL;
} else {
    echo 'Database exists' . PHP_EOL;
}
